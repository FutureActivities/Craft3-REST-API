<?php
namespace futureactivities\rest\migrations;

use craft\db\Migration;
use craft\db\Query;

class Install extends Migration
{
    public function safeUp()
    {
        if (!$this->db->tableExists('{{%usertokens}}')) {
            
            $this->createTable('{{%usertokens}}', [
                'id' => $this->primaryKey(),
                'token' => $this->text(),
                'userId' => $this->integer()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);
        }
    }

    public function safeDown()
    {
        
    }
}