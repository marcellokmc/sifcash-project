<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use App\Traits\Auditable;
    
    class Paiement extends Model
    {
        use HasFactory, Auditable;

        protected $fillable = [
            'adhesion_id',
            'adherent_id',
            'montant',
            'categorie',
            'mode_paiement',
            'preuve',
            'reference_paiement',
            'numero_compte_beneficiaire',
            'banque_emetteur',
            'reference_cheque',
            'statut',
            'motif_rejet',
            'date_soumission',
            'date_validation',
            'validated_by_agent_id',
        ];

        protected $casts = [
            'montant' => 'decimal:2',
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
        ];

        // Relations
        public function adhesion()
        {
            return $this->belongsTo(Adhesion::class);
        }

        public function adherent()
        {
            return $this->belongsTo(Adherent::class);
        }

        public function validatedByAgent()
        {
            return $this->belongsTo(User::class, 'validated_by_agent_id');
        }

        public function details()
        {
            return $this->hasMany(PaiementDetail::class);
        }

        public function piecesJointes()
        {
            return $this->hasMany(PaiementPieceJointe::class);
        }
    }
