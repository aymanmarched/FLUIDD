<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
     protected $fillable = [
        'reference','kind','client_id','technicien_user_id',
        'status','current_step',
        'will_fix','cannot_fix_reason','propose_remplacer','proposal_status',
        'will_install','cannot_install_reason',
        'paid'
    ];

    protected $casts = [
        'will_fix' => 'boolean',
        'propose_remplacer' => 'boolean',
        'will_install' => 'boolean',
        'paid' => 'boolean',
    ];
   public function steps() { return $this->hasMany(MissionStep::class); }
    public function recommendations() { return $this->hasMany(MissionRecommendation::class); }
    public function proposal() { return $this->hasOne(ConversionProposal::class); }

    public function client() { return $this->belongsTo(Client::class); }
    public function technicienUser() { return $this->belongsTo(User::class, 'technicien_user_id'); }
}
