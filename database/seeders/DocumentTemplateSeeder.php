<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentTemplate::create([
            'title' => 'Affidavit of Change of Name',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_for_Name_Change.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Addition of Name',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Addition_of_Name.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Change of Ownership',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Change_of_Ownership.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Bachelorhood',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Bachelorhood.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of BVN Amendment',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_BVN_Amendment.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Change of Ownership',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Change_of_Ownership.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Citizenship',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Citizenship.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Confirmation of Relationship',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Confirmation_of_Relationship.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Death',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Death.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Domicile',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Domicile.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Guardianship',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Guardianship.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Identity Theft',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Identity_Theft.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Loss',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Loss.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Marriage',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Marriage.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Proof of Ownership',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Proof_of_Ownership.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Residence',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Residence.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Spinisterhood',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Spinisterhood.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Sponsorship',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Sponsorship.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of State of Origin',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_State_of_Origin.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Sworn Declaration of Age',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Sworn_Declaration_of_Age.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);

        DocumentTemplate::create([
            'title' => 'Affidavit of Undertaking of Good Conduct',
            'file' => config('externallinks.s3_storage_url').'templates/affidavit_template/Affidavit_of_Undertaking_of_Good_Conduct.pdf',
            'type' => null,
            'templatable_type' => null,
            'templatable_id' => null,
            'public' => true,
        ]);
    }
}
