<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\SchemaHelper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoleAndPermission, Notifiable, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activated',
        'token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'locale',
        'phone_number',
        'password',
        'activated',
        'token',
        'signup_ip_address',
        'signup_confirmation_ip_address',
        'signup_sm_ip_address',
        'admin_ip_address',
        'updated_ip_address',
        'deleted_ip_address',
        'softland_user',
        'softland_store',
        'tipo_usuario',
        'cliente_codigo',
        'cliente_nombre',
        'vendedor_codigo',
        'vendedor_nombre',
        'vendedor_id',
        'is_sales_manager',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'first_name'                        => 'string',
        'last_name'                         => 'string',
        'email'                             => 'string',
        'password'                          => 'string',
        'activated'                         => 'boolean',
        'token'                             => 'string',
        'signup_ip_address'                 => 'string',
        'signup_confirmation_ip_address'    => 'string',
        'signup_sm_ip_address'              => 'string',
        'admin_ip_address'                  => 'string',
        'updated_ip_address'                => 'string',
        'deleted_ip_address'                => 'string',
        'cliente_codigo'                    => 'string',
        'cliente_nombre'                    => 'string',
        'created_at'                        => 'datetime',
        'updated_at'                        => 'datetime',
        'deleted_at'                        => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the socials for the user.
     */
    public function social()
    {
        return $this->hasMany(\App\Models\Social::class);
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(\App\Models\Profile::class);
    }

    /**
     * The profiles that belong to the user.
     */
    public function profiles()
    {
        return $this->belongsToMany(\App\Models\Profile::class)->withTimestamps();
    }

    /**
     * Check if a user has a profile.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasProfile($name)
    {
        foreach ($this->profiles as $profile) {
            if ($profile->name === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add/Attach a profile to a user.
     *
     * @param  Profile  $profile
     */
    public function assignProfile(Profile $profile)
    {
        return $this->profiles()->attach($profile);
    }

    /**
     * Remove/Detach a profile to a user.
     *
     * @param  Profile  $profile
     */
    public function removeProfile(Profile $profile)
    {
        return $this->profiles()->detach($profile);
    }

    /**
     * Conversaciones donde el usuario es user_one
     */
    public function conversationsAsUserOne()
    {
        return $this->hasMany(Conversation::class, 'user_one');
    }

    /**
     * Conversaciones donde el usuario es user_two
     */
    public function conversationsAsUserTwo()
    {
        return $this->hasMany(Conversation::class, 'user_two');
    }

    /**
     * Todas las conversaciones del usuario
     */
    public function conversations()
    {
        return Conversation::where('user_one', $this->id)
            ->orWhere('user_two', $this->id)
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Mensajes enviados por el usuario
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from');
    }

    /**
     * Mensajes recibidos por el usuario
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to');
    }

    /**
     * Contar mensajes no leídos
     */
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    /**
     * Relación con conjuntos/empresas permitidas
     *
     * NOTA: No se puede usar belongsToMany directo porque Conjunto usa conexión 'softland'
     * y user_conjunto está en 'sqlsrv'. Usamos una query manual.
     */
    public function conjuntos()
    {
        $conjuntoCodes = \DB::connection('sqlsrv')
            ->table('user_conjunto')
            ->where('user_id', $this->id)
            ->pluck('conjunto');

        return Conjunto::whereIn('CONJUNTO', $conjuntoCodes);
    }

    /**
     * Obtener los conjuntos como colección (método auxiliar)
     */
    public function getConjuntosAttribute()
    {
        return $this->conjuntos()->get();
    }

    /**
     * Obtener el conjunto por defecto del usuario
     */
    public function conjuntoPorDefecto()
    {
        $defaultConjunto = \DB::table('user_conjunto')
            ->where('user_id', $this->id)
            ->where('is_default', true)
            ->first();

        if ($defaultConjunto) {
            return Conjunto::where('CONJUNTO', $defaultConjunto->conjunto)->first();
        }

        // Si no hay default, retornar el primero
        return $this->conjuntos()->first();
    }

    /**
     * Verificar si el usuario tiene acceso a un conjunto específico
     */
    public function tieneAccesoConjunto($codigoConjunto)
    {
        return \DB::table('user_conjunto')
            ->where('user_id', $this->id)
            ->where('conjunto', $codigoConjunto)
            ->exists();
    }

    /**
     * Establecer un conjunto como predeterminado
     */
    public function setConjuntoPorDefecto($codigoConjunto)
    {
        // Remover default de todos
        \DB::table('user_conjunto')
            ->where('user_id', $this->id)
            ->update(['is_default' => false]);

        // Establecer el nuevo default
        \DB::table('user_conjunto')
            ->where('user_id', $this->id)
            ->where('conjunto', $codigoConjunto)
            ->update(['is_default' => true]);
    }

    /**
     * Obtener las bodegas permitidas para este usuario desde Softland
     * Basado en la relación C01.USUARIO_BODEGA
     */
    public function bodegasPermitidas()
    {
        if (!$this->softland_user) {
            return collect([]);
        }

        // Obtener códigos de bodegas desde USUARIO_BODEGA con esquema dinámico
        $schema = SchemaHelper::getSchema();
        $codigosBodegas = \DB::connection('softland')
            ->table($schema . '.USUARIO_BODEGA')
            ->where('USUARIO', $this->softland_user)
            ->pluck('BODEGA');

        if ($codigosBodegas->isEmpty()) {
            return collect([]);
        }

        // Obtener detalles completos de las bodegas
        return Bodega::whereIn('BODEGA', $codigosBodegas)->get();
    }

    /**
     * Verificar si el usuario tiene acceso a una bodega específica
     */
    public function tieneAccesoBodega($codigoBodega)
    {
        if (!$this->softland_user) {
            return false;
        }

        $schema = SchemaHelper::getSchema();
        return \DB::connection('softland')
            ->table($schema . '.USUARIO_BODEGA')
            ->where('USUARIO', $this->softland_user)
            ->where('BODEGA', $codigoBodega)
            ->exists();
    }

    /**
     * Menús asignados al usuario
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_user')->withTimestamps();
    }

    /**
     * Obtener menús jerárquicos del usuario
     */
    public function getMenusJerarquicos()
    {
        return Menu::getMenusParaUsuario($this->id);
    }

    /**
     * Relación con tabla Vendedor de Softland
     */
    public function vendedorSoftland()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id', 'VENDEDOR');
    }

    /**
     * Token de autenticación de Microsoft OAuth
     */
    public function microsoftToken()
    {
        return $this->hasOne(MicrosoftToken::class);
    }

    /**
     * Verificar si el usuario es gerente de ventas para CRM
     */
    public function isSalesManager()
    {
        return $this->is_sales_manager == true;
    }

    /**
     * Verificar si el usuario es vendedor
     */
    public function isSalesperson()
    {
        return !empty($this->vendedor_id) && !$this->is_sales_manager;
    }

    /**
     * Scope para obtener solo gerentes de ventas
     */
    public function scopeSalesManagers($query)
    {
        return $query->where('is_sales_manager', true);
    }

    /**
     * Scope para obtener solo vendedores
     */
    public function scopeSalespeople($query)
    {
        return $query->whereNotNull('vendedor_id')->where('is_sales_manager', false);
    }
}
