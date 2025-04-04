<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = 'clients';
    protected $fillable = [
        'instagram_id',
        'username',
        'profile_picture_url',
        'permissions', 
    ];

    // Définir le cast pour la colonne permissions (pour la convertir automatiquement en tableau)
    protected $casts = [
        'permissions' => 'array', // Permet de manipuler 'permissions' comme un tableau
    ];

    public function posts()
    {
        return $this->hasMany(posts::class, 'client_id'); // relation inverse
    }
}
