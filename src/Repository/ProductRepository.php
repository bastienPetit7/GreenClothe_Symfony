<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requête qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
     public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c');
            

         if(!empty($search->categories))
        {
            $query = $query 
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories); 
                
        }

         if(!empty($search->string))
        {
            $query = $query 
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        return $query->getQuery()->getResult(); 
    }
    

     public function findWithNavbar(int $id)
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c') 
            ->andWhere('c.id = :id')
            ->setParameter('id', $id ); 

        return $query->getQuery()->getResult(); 
    }


    public function findCategoryId(int $id)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * 
        FROM product p
        INNER JOIN product_category x ON p.id = x.product_id
        INNER JOIN category c ON c.id = x.category_id 
        WHERE p.id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt -> executeStatement();

        return $stmt->fetchAllAssociative();

    }
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
