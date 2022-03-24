<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324141803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Data Seeding';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO `category` VALUES (1,"Tornavida Setleri",NULL, NOW(),NULL),(2,"Elektrik Anahtarları",NULL, NOW(),NULL);');
        $this->addSql('INSERT INTO `customer` VALUES (1,"Osman ZÜLFİGAROĞLU","contact@osmanzulfigaroglu.com",NULL,"1234567",0.00,NULL, NOW(),NULL);');
        $this->addSql('INSERT INTO `discount` VALUES (1,NULL,"10_PERCENT_OVER_1000","total_price","higher_than_value",1000,"order","discount_by_percantage",10,NULL, NOW(),NULL),(2,2,"BUY_5_GET_1","product_quantity","each_times_of_value",6,"any_item","give_free",NULL,NULL, NOW(),NULL),(3,1,"BUY_5_GET_1","item_count","higher_than_value",6,"cheapest_item","discount_by_percantage",20,NULL, NOW(),NULL);');
        $this->addSql('INSERT INTO `product` VALUES (1,1,"Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti",120.75,10,NULL, NOW(),NULL),(2,1,"Reko Mini Tamir Hassas Tornavida Seti 32\'li",49.50,10,NULL, NOW(),NULL),(3,2,"Viko Karre Anahtar - Beyaz",11.28,10,NULL, NOW(),NULL),(4,2,"Legrand Salbei Anahtar, Alüminyum",22.80,10,NULL, NOW(),NULL),(5,2,"Schneider Asfora Beyaz Komütatör",12.95,10,NULL, NOW(),NULL);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE `category`;');
        $this->addSql('TRUNCATE `customer`;');
        $this->addSql('TRUNCATE `discount`;');
        $this->addSql('TRUNCATE `product`;');
    }
}
