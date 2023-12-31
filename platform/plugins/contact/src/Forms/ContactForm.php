<?php

namespace Cmat\Contact\Forms;

use Assets;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Contact\Enums\ContactStatusEnum;
use Cmat\Contact\Http\Requests\EditContactRequest;
use Cmat\Contact\Models\Contact;

class ContactForm extends FormAbstract
{
    public function buildForm(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/contact/js/contact.js')
            ->addStylesDirectly('vendor/core/plugins/contact/css/contact.css');

        $this
            ->setupModel(new Contact())
            ->setValidatorClass(EditContactRequest::class)
            ->withCustomFields()
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => ContactStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/contact::contact.contact_information'),
                    'content' => view('plugins/contact::contact-info', ['contact' => $this->getModel()])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
                // 'replies' => [
                //     'title' => trans('plugins/contact::contact.replies'),
                //     'content' => view('plugins/contact::reply-box', ['contact' => $this->getModel()])->render(),
                // ],
            ]);
    }
}
