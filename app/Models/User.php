<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    public function getAadharData(){
        return $this->hasOne(AadharData::class, 'user_id', 'id');
    }
    public function getBankData(){
        return $this->hasOne(BankData::class, 'user_id', 'id');
    }
    public function getCompanyData(){
        return $this->hasOne(CompanyData::class, 'user_id', 'id');
    }
}
