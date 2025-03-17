<?php
namespace Models;

class RentalModel extends ModeleParent
{
    // Récupère tous les produits (exemple)
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupère un produit selon son ID
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Méthode pour insérer un produit "classique"
    public function add($data)
    {
        $sql = "INSERT INTO products 
                (name, description, price, discount_price, stock, category, brand, 
                 id_categories, id_ss_categories, gender, gender_child, code_ena, size, image)
                VALUES
                (:name, :description, :price, :discount_price, :stock, :category, :brand,
                 :id_categories, :id_ss_categories, :gender, :gender_child, :code_ena, :size, :image)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    
//    "location"
    
    public function addRental(array $data)
    {
        $sql = "INSERT INTO rentals 
                (user_id, product_id, start_date, end_date, total_price, status)
                VALUES 
                (:user_id, :product_id, :start_date, :end_date, :total_price, :status)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}
