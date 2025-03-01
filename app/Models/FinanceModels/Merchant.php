<?php
namespace App\Models\FinanceModels;

class Merchant
{
    public $id;
    public $name;
    public $contact_person;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $country;
    public $website;

    /**
     * Search merchants by name.
     *
     * @param string $term The search term.
     * @return array An array of Merchant objects.
     */
    public static function search($term)
    {
        // Get a PDO connection from your Database class.
        $db = \App\Database::connect();
        $sql = "SELECT id, name, contact_person, email, phone, address, city, state, zip, country, website
                FROM finance_merchants
                WHERE name LIKE :term
                LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute(['term' => '%' . $term . '%']);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $merchant = new self();
            $merchant->id             = $row['id'];
            $merchant->name           = $row['name'];
            $merchant->contact_person = $row['contact_person'];
            $merchant->email          = $row['email'];
            $merchant->phone          = $row['phone'];
            $merchant->address        = $row['address'];
            $merchant->city           = $row['city'];
            $merchant->state          = $row['state'];
            $merchant->zip            = $row['zip'];
            $merchant->country        = $row['country'];
            $merchant->website        = $row['website'];
            $results[] = $merchant;
        }
        return $results;
    }
}
