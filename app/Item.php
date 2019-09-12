<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;

class Item extends Model
{
    use ElasticquentTrait;

    public $fillable = ['title','description'];

    protected $mappingProperties = array(
      'title' => [
        'type' => 'string',
        "analyzer" => "standard",
      ],
      'description' => [
        'type' => 'string',
        "analyzer" => "standard",
      ],
    );
}
