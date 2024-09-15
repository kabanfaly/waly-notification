<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function index()
    {
        return view('settings.index', ['studentFees' => $this->getStudentFees(), 'professionalFees' => $this->getProfessionalFees()]);
    }

    private function getStudentFees()
    {
        $studentFees = 20;
        $studentMember = DB::table('VbE_wpforms_entry_fields')
        ->select('value')
        ->where('field_id', 130)
        ->first();

        if($studentMember)
        {
            $studentFees = formatAmount($studentMember->value);
        }
        return $studentFees;
    }

    private function getProfessionalFees()
    {
        $professionalMember = DB::table('VbE_wpforms_entry_fields')
        ->select('value')
        ->where('field_id', 129)
        ->first();

        if($professionalMember)
        {
            $professionalFees = formatAmount($professionalMember->value);
        }
        return $professionalFees;
    }

    public function changeStudentFees(Request $request)
    {
        $fees = number_format($request['fees'], 2);
        $this->updateFees($fees, true);
        return redirect('/settings')->with('message', "Étudiant(e) : La modification des frais d'adhésion annuels a été effectuée avec succès");
    }


    public function showStudentFees()
    {
        return view('settings.students', ['fees' => $this->getStudentFees()]);
    }

    public function changeProfessionalFees(Request $request)
    {
        $fees = number_format($request['fees'], 2);
        $this->updateFees($fees, false);
        return redirect('/settings')->with('message', "Professionnel(e) : La modification des frais d'adhésion annuels a été effectuée avec succès");
    }

    private function updateFees($fees, $isStudent)
    {
        // update status
        DB::table('VbE_wpforms_entry_fields')
        ->where('field_id', $isStudent ? 143 : 88)
        ->update(
            [
                'value' =>  ($isStudent ? 'Étudiant(e) - &#36; ': 'Professionnel(le) - &#36; ') . $fees
            ],
        );

        // amount
        DB::table('VbE_wpforms_entry_fields')
        ->where('field_id', $isStudent ? 130 : 129)
        ->update(
            [
                'value' =>  '&#36; ' . $fees
            ],
        );
    }

    public function showProfessionalFees()
    {
        return view('settings.professionals', ['fees' => $this->getProfessionalFees()]);
    }
}
