<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name : The name of the domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature-based modular domain structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $domainName = Str::studly($this->argument('name'));
        $domainPath = app_path("Domains/{$domainName}");

        if (File::exists($domainPath)) {
            $this->error("Domain {$domainName} already exists!");
            return self::FAILURE;
        }

        $this->info("Creating domain: {$domainName}...");

        $folders = [
            'Actions',
            'DTOs',
            'Models',
            'Policies',
            'Requests',
            'Resources',
            'Repositories/Contracts',
            'Repositories/Eloquent',
            'Services',
            'Interfaces',
            'Enums',
            'Events',
            'Listeners',
            'Notifications',
            'Jobs',
            'Exceptions',
            'Traits',
            'Rules',
            'Observers',
            'Factories',
            'Seeders',
            'Tests/Unit',
            'Tests/Feature',
        ];

        foreach ($folders as $folder) {
            File::makeDirectory("{$domainPath}/{$folder}", 0755, true);
        }

        $this->generateModel($domainName, $domainPath);
        $this->generateRepositoryInterface($domainName, $domainPath);
        $this->generateRepositoryEloquent($domainName, $domainPath);
        $this->generateRequest($domainName, $domainPath);
        $this->generateResource($domainName, $domainPath);
        $this->generatePolicy($domainName, $domainPath);
        $this->generateAction($domainName, $domainPath);
        $this->generateTests($domainName, $domainPath);
        $this->generateServiceProvider($domainName, $domainPath);
        $this->generateEnum($domainName, $domainPath);
        $this->generateEvent($domainName, $domainPath);
        $this->generateListener($domainName, $domainPath);
        $this->generateNotification($domainName, $domainPath);
        $this->generateJob($domainName, $domainPath);
        $this->generateException($domainName, $domainPath);
        $this->generateTrait($domainName, $domainPath);
        $this->generateRule($domainName, $domainPath);
        $this->generateInterface($domainName, $domainPath);

        $this->info("Domain {$domainName} created successfully!");
        return self::SUCCESS;
    }

    private function generateModel(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$domain} extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected \$fillable = [
        // Define fillable attributes
    ];

    protected \$casts = [
        // Define casts
    ];
}
PHP;

        File::put("{$path}/Models/{$domain}.php", $content);
    }

    private function generateRepositoryInterface(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Repositories\Contracts;

use App\Shared\Contracts\RepositoryInterface;

interface {$domain}RepositoryInterface extends RepositoryInterface
{
    // Define domain specific database operations
}
PHP;

        File::put("{$path}/Repositories/Contracts/{$domain}RepositoryInterface.php", $content);
    }

    private function generateRepositoryEloquent(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Repositories\Eloquent;

use App\Domains\\{$domain}\Models\\{$domain};
use App\Domains\\{$domain}\Repositories\Contracts\\{$domain}RepositoryInterface;
use App\Shared\Services\BaseRepository;

class {$domain}Repository extends BaseRepository implements {$domain}RepositoryInterface
{
    protected function model(): string
    {
        return {$domain}::class;
    }
}
PHP;

        File::put("{$path}/Repositories/Eloquent/{$domain}Repository.php", $content);
    }

    private function generateRequest(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Create{$domain}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define validation rules
        ];
    }
}
PHP;

        File::put("{$path}/Requests/Create{$domain}Request.php", $content);
    }

    private function generateResource(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$domain}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
            'created_at' => \$this->created_at,
            'updated_at' => \$this->updated_at,
        ];
    }
}
PHP;

        File::put("{$path}/Resources/{$domain}Resource.php", $content);
    }

    private function generatePolicy(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Policies;

use App\Domains\User\Models\User;
use App\Domains\\{$domain}\Models\\{$domain};

class {$domain}Policy
{
    public function viewAny(User \$user): bool
    {
        return true;
    }

    public function view(User \$user, {$domain} \$model): bool
    {
        return true;
    }

    public function create(User \$user): bool
    {
        return true;
    }

    public function update(User \$user, {$domain} \$model): bool
    {
        return true;
    }

    public function delete(User \$user, {$domain} \$model): bool
    {
        return true;
    }
}
PHP;

        File::put("{$path}/Policies/{$domain}Policy.php", $content);
    }

    private function generateAction(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\DTOs\Create{$domain}DTO;
use App\Domains\\{$domain}\Models\\{$domain};
use App\Domains\\{$domain}\Repositories\Contracts\\{$domain}RepositoryInterface;

class Create{$domain}Action
{
    public function __construct(
        protected {$domain}RepositoryInterface \$repository
    ) {}

    public function execute(Create{$domain}DTO \$dto): {$domain}
    {
        /** @var {$domain} */
        return \$this->repository->create(\$dto->toArray());
    }
}
PHP;

        File::put("{$path}/Actions/Create{$domain}Action.php", $content);

        $dtoContent = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\DTOs;

use App\Shared\DTOs\BaseDTO;

class Create{$domain}DTO extends BaseDTO
{
    public function __construct(
        // Define read-only properties
    ) {}
}
PHP;

        File::put("{$path}/DTOs/Create{$domain}DTO.php", $dtoContent);
    }

    private function generateTests(string $domain, string $path): void
    {
        $unitTest = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Tests\Unit;

use Tests\TestCase;

class {$domain}UnitTest extends TestCase
{
    public function test_example(): void
    {
        \$this->assertTrue(true);
    }
}
PHP;

        File::put("{$path}/Tests/Unit/{$domain}UnitTest.php", $unitTest);

        $featureTest = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Tests\Feature;

use Tests\TestCase;

class {$domain}FeatureTest extends TestCase
{
    public function test_example(): void
    {
        \$this->assertTrue(true);
    }
}
PHP;

        File::put("{$path}/Tests/Feature/{$domain}FeatureTest.php", $featureTest);
    }

    private function generateServiceProvider(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain};

use App\Domains\\{$domain}\Repositories\Contracts\\{$domain}RepositoryInterface;
use App\Domains\\{$domain}\Repositories\Eloquent\\{$domain}Repository;
use Illuminate\Support\ServiceProvider;

class {$domain}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \$this->app->bind(
            {$domain}RepositoryInterface::class,
            {$domain}Repository::class
        );
    }

    public function boot(): void
    {
        // Load migrations, policies, routes if applicable
        if (\$this->app->runningInConsole()) {
            // Load migrations dynamically from Domain folder
            \$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }
}
PHP;

        File::put("{$path}/{$domain}ServiceProvider.php", $content);
    }

    private function generateEnum(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Enums;

enum {$domain}Status: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
PHP;

        File::put("{$path}/Enums/{$domain}Status.php", $content);
    }

    private function generateEvent(string $domain, string $path): void
    {
        $lowercase = lcfirst($domain);
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Events;

use App\Domains\\{$domain}\Models\\{$domain};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$domain}Created
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public {$domain} \${$lowercase}
    ) {}
}
PHP;

        File::put("{$path}/Events/{$domain}Created.php", $content);
    }

    private function generateListener(string $domain, string $path): void
    {
        $lowercase = lcfirst($domain);
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Listeners;

use App\Domains\\{$domain}\Events\\{$domain}Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class Log{$domain}Created implements ShouldQueue
{
    public function handle({$domain}Created \$event): void
    {
        Log::info('{$domain} created event captured for ID: ' . \$event->{$lowercase}->id);
    }
}
PHP;

        File::put("{$path}/Listeners/Log{$domain}Created.php", $content);
    }

    private function generateNotification(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class {$domain}Notification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object \$notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('{$domain} Notification Alert')
            ->line('Your {$domain} record has been processed successfully.')
            ->action('View Details', url('/'))
            ->line('Thank you for using LaraforgeX!');
    }
}
PHP;

        File::put("{$path}/Notifications/{$domain}Notification.php", $content);
    }

    private function generateJob(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Process{$domain}Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Execute background domain process logic
    }
}
PHP;

        File::put("{$path}/Jobs/Process{$domain}Job.php", $content);
    }

    private function generateException(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Exceptions;

use Exception;

class {$domain}Exception extends Exception
{
    public static function operationFailed(string \$reason = ''): self
    {
        return new self("{$domain} operation failed: {\$reason}", 400);
    }
}
PHP;

        File::put("{$path}/Exceptions/{$domain}Exception.php", $content);
    }

    private function generateTrait(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Traits;

trait Has{$domain}
{
    // Define shared domain functionality
}
PHP;

        File::put("{$path}/Traits/Has{$domain}.php", $content);
    }

    private function generateRule(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class {$domain}Rule implements ValidationRule
{
    public function validate(string \$attribute, mixed \$value, Closure \$fail): void
    {
        // Custom domain parameter validation check
    }
}
PHP;

        File::put("{$path}/Rules/{$domain}Rule.php", $content);
    }

    private function generateInterface(string $domain, string $path): void
    {
        $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Interfaces;

interface {$domain}Interface
{
    // Custom domain contracts
}
PHP;

        File::put("{$path}/Interfaces/{$domain}Interface.php", $content);
    }
}
