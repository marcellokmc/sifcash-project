<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEpargneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // L'autorisation est gérée par les politiques
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $epargne = $this->route('epargne');
        
        return [
            'adherent_id' => [
                'required',
                'exists:adherents,id',
                // Vérifier que l'adhérent n'a pas déjà un compte du même type (sauf celui-ci)
                Rule::unique('epargnes')
                    ->where('adherent_id', $this->adherent_id)
                    ->where('type_epargne', $this->type_epargne)
                    ->where('statut', '!=', 'cloture')
                    ->ignore($epargne->id)
            ],
            'type_epargne' => [
                'required',
                Rule::in(array_keys(\App\Models\Epargne::TYPES_EPARGNE)),
            ],
            'montant_initial' => [
                'required',
                'numeric',
                'min:1000', // Minimum 1000 FCFA
                'max:10000000', // Maximum 10 000 000 FCFA
            ],
            'taux_interet' => [
                'required',
                'numeric',
                'min:0.1',
                'max:100',
            ],
            'date_ouverture' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'statut' => [
                'required',
                Rule::in(array_keys(\App\Models\Epargne::STATUTS)),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'adherent_id.unique' => 'Cet adhérent a déjà un compte épargne de ce type.',
            'montant_initial.min' => 'Le montant minimum pour un compte est de 1 000 FCFA.',
            'montant_initial.max' => 'Le montant maximum pour un compte est de 10 000 000 FCFA.',
            'taux_interet.min' => 'Le taux d\'intérêt doit être d\'au moins 0.1%.',
            'taux_interet.max' => 'Le taux d\'intérêt ne peut pas dépasser 100%.',
            'date_ouverture.before_or_equal' => 'La date d\'ouverture ne peut pas être dans le futur.',
        ];
    }
}
