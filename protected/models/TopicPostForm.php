<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 7/30/12
 * Time: 5:46 PM
 * To change this template use File | Settings | File Templates.
 */
class TopicPostForm extends CFormModel
{
    public $title;
    public $slug;
    public $poster_id;
    public $forum_id;
    public $num_hits;
    public $created_at;
    public $topic_id;
    public $last_reply_on;
    public $last_reply_user;

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to perform any action
                'create' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // Topic
            array('title, body', 'required'),
            array('poster_id, forum_id, num_hits', 'numerical', 'integerOnly' => true),
            array('is_active, is_sticky, is_locked', 'boolean'),
            array('title', 'length', 'max' => 255),
            array('slug', 'length', 'max' => 80),
            array('last_reply_user', 'length', 'max' => 48),
            array('created_at, last_reply_on', 'safe'),

            // Post
            array('topic_id, poster_id, edited_by, poster_ip', 'numerical', 'integerOnly' => true),
            array('is_edited', 'boolean'),
            array('updated_at, poster_ip', 'safe'),


            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('id, is_active, is_sticky, is_locked, title, poster_id, forum_id, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'is_active' => 'Is Active?',
            'is_sticky' => 'Is Sticky?',
            'is_locked' => 'Is Locked?',
            'title' => 'Title',
            'slug' => 'Slug',
            'poster_id' => 'Poster',
            'forum_id' => 'Forum',
            'num_hits' => 'Number of Hits',
            'created_at' => 'Created At',
            'last_reply_on' => 'Last Reply On',
            'last_reply_user' => 'Last Reply User',
        );
    }

    // set some default values before creating a new record
    protected function beforeSave()
    {
        // TODO: $this->forum_id
        $this->slug = Topic::slugify($this->title);
        $this->created_at = new CDbExpression('NOW()');
        $this->poster_id = Yii::app()->user->id;
        $user = User::model()->findByPk($this->poster_id);
        $this->last_reply_user = $user->name;
        $this->last_reply_on = new CDbExpression('NOW()');
        $this->num_hits = 1;
    }
}