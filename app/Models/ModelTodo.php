<?php
namespace
    App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'todo'; //nama table yang kita buat lewat migration adalah todo
}
