<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PortfolioWorkAuthor extends Pivot
{
    use HasFactory;

    protected $table = 'portfolio_work_authors';

    protected $fillable = [
        'portfolio_work_id',
        'author_id',
        'role'
    ];

    public $incrementing = true;

    /**
     * Relacionamento com trabalho de portfólio
     */
    public function portfolioWork()
    {
        return $this->belongsTo(PortfolioWork::class);
    }

    /**
     * Relacionamento com autor
     */
    public function author()
    {
        return $this->belongsTo(Autor::class);
    }

    /**
     * Scope para filtrar por papel/função
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Método para obter papéis disponíveis
     */
    public static function getAvailableRoles()
    {
        return [
            'designer' => 'Designer',
            'developer' => 'Desenvolvedor',
            'project_manager' => 'Gerente de Projeto',
            'copywriter' => 'Redator',
            'photographer' => 'Fotógrafo',
            'illustrator' => 'Ilustrador',
            'ux_designer' => 'UX Designer',
            'ui_designer' => 'UI Designer',
            'frontend_developer' => 'Desenvolvedor Frontend',
            'backend_developer' => 'Desenvolvedor Backend',
            'fullstack_developer' => 'Desenvolvedor Fullstack',
            'consultant' => 'Consultor',
            'coordinator' => 'Coordenador',
            'other' => 'Outro'
        ];
    }

    /**
     * Accessor para papel formatado
     */
    public function getFormattedRoleAttribute()
    {
        $roles = static::getAvailableRoles();
        return $roles[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }
}