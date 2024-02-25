<?php

namespace App\Rules;

use App\Models\Document;
use Illuminate\Contracts\Validation\Rule;

class DocumentSigningSequence implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $document;

    public function __construct($id)
    {
        $this->document = Document::find($id);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->document?->has_approval_sequence == true && ! ($value === null);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A sequence action is required of this you can edit the document by turning off the sequence action';
    }
}
