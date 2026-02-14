<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereValue($value)
 */
	class AppSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $tenant_id
 * @property string $action
 * @property string|null $target_type
 * @property string|null $target_id
 * @property string|null $ip_address
 * @property string|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUserId($value)
 */
	class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $type
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Broadcast whereUpdatedAt($value)
 */
	class Broadcast extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string|null $document_type
 * @property string $jenis_dokumen
 * @property string $file_path
 * @property string|null $keterangan
 * @property int $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereJenisDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $graduation_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation whereGraduationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Graduation whereUpdatedAt($value)
 */
	class Graduation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $file_name
 * @property int $total_rows
 * @property int $success_rows
 * @property int $failed_rows
 * @property int|null $imported_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ImportFailure> $failures
 * @property-read int|null $failures_count
 * @property-read \App\Models\TenantUser|null $importer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereFailedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereImportedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereSuccessRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereUpdatedAt($value)
 */
	class Import extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $import_id
 * @property int $row_number
 * @property string $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Import $import
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereImportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereRowNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportFailure whereUpdatedAt($value)
 */
	class ImportFailure extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $used_space
 * @property \Illuminate\Support\Carbon|null $last_calculated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage whereLastCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsage whereUsedSpace($value)
 */
	class StorageUsage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nisn
 * @property string|null $nik
 * @property string $nama
 * @property string|null $birth_place
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $address
 * @property string|null $parent_name
 * @property string|null $year_in
 * @property string|null $year_out
 * @property string|null $kelas
 * @property string $status_kelulusan
 * @property string|null $tahun_lulus
 * @property string|null $foto_profil
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \App\Models\Graduation|null $graduation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereBirthPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFotoProfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereParentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStatusKelulusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereYearIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereYearOut($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $npsn
 * @property string|null $nama_sekolah
 * @property string|null $jenjang
 * @property string|null $alamat
 * @property int $status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $data
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Stancl\Tenancy\Database\Models\Domain> $domains
 * @property-read int|null $domains_count
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNamaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNpsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $logo
 * @property string|null $subscription_plan
 * @property int $storage_limit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereStorageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereSubscriptionPlan($value)
 */
	class Tenant extends \Eloquent implements \Stancl\Tenancy\Contracts\TenantWithDatabase {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser whereUpdatedAt($value)
 */
	class TenantUser extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

