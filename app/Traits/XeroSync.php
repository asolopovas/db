<?php


namespace App\Traits;

use App\Services\XeroService;
use App\Models\Customer;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Phone;
use Illuminate\Support\Facades\Log;

trait XeroSync
{
    public function syncContactToXero(Customer $customer, bool $isUpdate = false)
    {
        try {

            $xero_service = new XeroService();
            $xero_instance = $xero_service->getXeroInstance();
            if ($isUpdate && $customer->xero_contact_id) {
                $contact = $xero_instance->loadByGUID(Contact::class, $customer->xero_contact_id);
            } else {
                $contact = new Contact($xero_instance);
            }

            // Set basic fields
            $name = $customer->company ?: trim($customer->firstname . ' ' . $customer->lastname);
            $contact->setName($name)
                ->setContactStatus($customer->active)
                ->setFirstName($customer->firstname)
                ->setLastName($customer->lastname)
                ->setEmailAddress($customer->email1)
                ->setIsCustomer(true);

            // Add Address
            $address = new Address($xero_instance);
            $address->setAddressType(Address::ADDRESS_TYPE_POBOX)
                ->setAddressLine1($customer->address_line_1)
                ->setAddressLine2($customer->address_line_2)
                ->setRegion($customer->region)
                ->setCity($customer->city)
                ->setRegion($customer->county)
                ->setPostalCode($customer->postcode)
                ->setCountry($customer->country);
            $contact->addAddress($address);

            // Add Phones
            if (!empty($customer->phone)) {
                $defaultPhone = new Phone($xero_instance);
                $defaultPhone->setPhoneType(Phone::PHONE_TYPE_DEFAULT)
                    ->setPhoneNumber($customer->phone);
                $contact->addPhone($defaultPhone);
            }

            if (!empty($customer->mobile_phone)) {
                $mobilePhone = new Phone($xero_instance);
                $mobilePhone->setPhoneType(Phone::PHONE_TYPE_MOBILE)
                    ->setPhoneNumber($customer->mobile_phone);
                $contact->addPhone($mobilePhone);
            }

            if (!$isUpdate) {
                $contact->setWebsite($customer->web_page);
            }

            $contact->save();

            if (!$isUpdate && $contact->getContactID()) {
                $customer->xero_contact_id = $contact->getContactID();
                $customer->save();
            }
        } catch (\Exception $e) {
            Log::error('Error syncing Xero contact', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    //
}
