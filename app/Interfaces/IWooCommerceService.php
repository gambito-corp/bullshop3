<?php
namespace App\Interfaces;
interface IWooCommerceService
{
    public function getAllCustomers();
    public function getCategories($id);
    public function createCategory($params);
    public function updateCategory($params, $id);
    public function deleteCategory($id);
}
