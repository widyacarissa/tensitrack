<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class StoreRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Or apply authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                ValidationRule::unique('rules')->ignore($this->rule), // 'rule' is the route parameter for update
            ],
            'description' => ['nullable', 'string'],
            'tingkat_risiko_id' => 'required|integer|exists:tingkat_risiko,id',
            'priority' => 'required|integer|min:0',

            'condition_groups' => 'required|array|min:1',
            'condition_groups.*.conditions' => 'required|array|min:1',
            
            'condition_groups.*.conditions.*.type' => [
                'required',
                'string',
                ValidationRule::in(['HAS_FAKTOR', 'FAKTOR_LAIN_COUNT', 'FAKTOR_TOTAL_COUNT', 'GAYA_HIDUP_COUNT']),
            ],
            'condition_groups.*.conditions.*.faktor_risiko_id' => [
                'nullable',
                'integer',
                // Required if type is HAS_FAKTOR
                ValidationRule::requiredIf(function ($attribute, $value) {
                    $conditionIndex = explode('.', $attribute)[3];
                    $groupIndex = explode('.', $attribute)[1];
                    return $this->input("condition_groups.{$groupIndex}.conditions.{$conditionIndex}.type") === 'HAS_FAKTOR';
                }),
                'exists:faktor_risiko,id',
            ],
            'condition_groups.*.conditions.*.operator' => [
                'required',
                'string',
                ValidationRule::in(['>=', '<=', '==', '!=', '>', '<']),
            ],
            'condition_groups.*.conditions.*.value' => [
                'required',
                'integer',
                // For HAS_FAKTOR, value must be 0 or 1
                ValidationRule::in([0, 1])->when(function ($input) {
                    $conditionIndex = explode('.', $input->currentPath())[3];
                    $groupIndex = explode('.', $input->currentPath())[1];
                    return $this->input("condition_groups.{$groupIndex}.conditions.{$conditionIndex}.type") === 'HAS_FAKTOR';
                }),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'condition_groups.required' => 'Setidaknya satu grup kondisi diperlukan.',
            'condition_groups.min' => 'Setidaknya satu grup kondisi diperlukan.',
            'condition_groups.*.conditions.required' => 'Setidaknya satu kondisi diperlukan untuk setiap grup.',
            'condition_groups.*.conditions.min' => 'Setidaknya satu kondisi diperlukan untuk setiap grup.',
            'condition_groups.*.conditions.*.type.in' => 'Tipe kondisi tidak valid.',
            'condition_groups.*.conditions.*.faktor_risiko_id.required_if' => 'Faktor Risiko diperlukan ketika tipe kondisi adalah "Memiliki Faktor".',
            'condition_groups.*.conditions.*.faktor_risiko_id.exists' => 'Faktor Risiko yang dipilih tidak valid.',
            'condition_groups.*.conditions.*.operator.in' => 'Operator kondisi tidak valid.',
            'condition_groups.*.conditions.*.value.in' => 'Nilai untuk tipe "Memiliki Faktor" harus 0 atau 1.',
        ];
    }
}