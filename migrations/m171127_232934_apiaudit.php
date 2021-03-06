<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;
use craft\queue\jobs\ResaveElements;
use craftcom\plugins\Plugin;

/**
 * m171124_232934_resave_plugins migration.
 */
class m171127_232934_apiaudit extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (($this->db = Craft::$app->get('logDb', false)) === null) {
            return;
        }

        $this->createTable('{{%request}}', [
            'id' => $this->primaryKey(),
            'url' => $this->text()->notNull(),
            'ip' => $this->string(45)->notNull(),
            'route' => $this->string()->notNull(),
            'verb' => $this->string(20)->notNull(),
            'bodyJson' => 'jsonb',
            'bodyText' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%errors}}', [
            'id' => $this->primaryKey(),
            'requestId' => $this->integer(),
            'message' => $this->text()->notNull(),
            'httpStatus' => $this->integer(),
            'stackTrace' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
            'requestId' => $this->integer(),
            'level' => $this->integer(),
            'category' => $this->string(),
            'message' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%keys}}', [
            'id' => $this->primaryKey(),
            'requestId' => $this->integer()->notNull(),
            'key' => $this->string(250)->notNull(),
            'plugin' => $this->string(250),
            'dateCreated' => $this->dateTime()->notNull(),
        ]);


        $this->createIndex(null, '{{%request}}', ['url']);
        $this->createIndex(null, '{{%errors}}', ['message']);
        $this->createIndex(null, '{{%errors}}', ['httpStatus']);
        $this->createIndex(null, '{{%logs}}', ['level']);
        $this->createIndex(null, '{{%logs}}', ['category']);
        $this->createIndex(null, '{{%keys}}', ['key']);

        $this->addForeignKey(null, '{{%errors}}', ['requestId'], '{{%request}}', ['id'], 'CASCADE');
        $this->addForeignKey(null, '{{%logs}}', ['requestId'], '{{%request}}', ['id'], 'CASCADE');
        $this->addForeignKey(null, '{{%keys}}', ['requestId'], '{{%request}}', ['id'], 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171127_232934_apiaudit cannot be reverted.\n";
        return false;
    }
}
