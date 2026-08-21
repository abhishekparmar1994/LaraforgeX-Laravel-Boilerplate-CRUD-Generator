<?php

declare(strict_types=1);

namespace App\Domains\CrudGenerator\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudCodeGenerator
{
    /**
     * Generate complete CRUD code files inside the target `codegenerator/{ModuleName}` directory.
     *
     * @param array<string, mixed> $payload
     * @return array{output_directory: string, generated_files: array<int, string>, files_content: array<string, string>}
     */
    public function generate(array $payload): array
    {
        $moduleName = Str::studly((string)($payload['module_name'] ?? 'CustomModule'));
        $modelName = Str::studly((string)($payload['model_name'] ?? $moduleName));
        $tableName = (string)($payload['table_name'] ?? Str::snake(Str::plural($modelName)));
        $columns = (array)($payload['columns'] ?? []);
        $relations = (array)($payload['relationships'] ?? []);
        $options = (array)($payload['options'] ?? []);

        // Root output directory inside `codegenerator/{ModuleName}`
        $outputDir = base_path("codegenerator/{$moduleName}");

        $filesContent = [
            // Domain Model
            "app/Domains/{$moduleName}/Models/{$modelName}.php" => $this->generateModelContent($moduleName, $modelName, $tableName, $columns, $relations),

            // DTOs
            "app/Domains/{$moduleName}/DTOs/Create{$modelName}DTO.php" => $this->generateCreateDtoContent($moduleName, $modelName, $columns),
            "app/Domains/{$moduleName}/DTOs/Update{$modelName}DTO.php" => $this->generateUpdateDtoContent($moduleName, $modelName, $columns),

            // Form Requests
            "app/Domains/{$moduleName}/Requests/Create{$modelName}Request.php" => $this->generateCreateRequestContent($moduleName, $modelName, $columns),
            "app/Domains/{$moduleName}/Requests/Update{$modelName}Request.php" => $this->generateUpdateRequestContent($moduleName, $modelName, $columns),

            // Actions
            "app/Domains/{$moduleName}/Actions/Create{$modelName}Action.php" => $this->generateCreateActionContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Actions/Update{$modelName}Action.php" => $this->generateUpdateActionContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Actions/Delete{$modelName}Action.php" => $this->generateDeleteActionContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Actions/BulkDelete{$modelName}Action.php" => $this->generateBulkDeleteActionContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Actions/Export{$modelName}Action.php" => $this->generateExportActionContent($moduleName, $modelName, $columns),

            // Repositories
            "app/Domains/{$moduleName}/Repositories/Contracts/{$modelName}RepositoryInterface.php" => $this->generateRepositoryInterfaceContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Repositories/Eloquent/{$modelName}Repository.php" => $this->generateRepositoryEloquentContent($moduleName, $modelName),

            // Services
            "app/Domains/{$moduleName}/Services/Contracts/{$modelName}ServiceInterface.php" => $this->generateServiceInterfaceContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Services/Eloquent/{$modelName}Service.php" => $this->generateServiceEloquentContent($moduleName, $modelName),

            // API Resources
            "app/Domains/{$moduleName}/Resources/{$modelName}Resource.php" => $this->generateResourceContent($moduleName, $modelName, $columns),
            "app/Domains/{$moduleName}/Resources/{$modelName}Collection.php" => $this->generateResourceCollectionContent($moduleName, $modelName),

            // Policy
            "app/Domains/{$moduleName}/Policies/{$modelName}Policy.php" => $this->generatePolicyContent($moduleName, $modelName),

            // Service Provider
            "app/Domains/{$moduleName}/{$moduleName}ServiceProvider.php" => $this->generateServiceProviderContent($moduleName, $modelName),

            // Controller
            "app/Domains/{$moduleName}/Http/Controllers/{$modelName}Controller.php" => $this->generateControllerContent($moduleName, $modelName, $columns),

            // Events & Listeners
            "app/Domains/{$moduleName}/Events/{$modelName}Created.php" => $this->generateEventContent($moduleName, $modelName),
            "app/Domains/{$moduleName}/Listeners/Log{$modelName}Activity.php" => $this->generateListenerContent($moduleName, $modelName),

            // Views
            "resources/views/admin/" . Str::snake($moduleName) . "/index.blade.php" => $this->generateIndexViewContent($moduleName, $modelName, $columns),
            "resources/views/admin/" . Str::snake($moduleName) . "/create.blade.php" => $this->generateCreateViewContent($moduleName, $modelName, $columns),
            "resources/views/admin/" . Str::snake($moduleName) . "/edit.blade.php" => $this->generateEditViewContent($moduleName, $modelName, $columns),
            "resources/views/admin/" . Str::snake($moduleName) . "/show.blade.php" => $this->generateShowViewContent($moduleName, $modelName, $columns),

            // Route Snippets
            "routes/api.php" => $this->generateApiRouteSnippet($moduleName, $modelName),
            "routes/web.php" => $this->generateWebRouteSnippet($moduleName, $modelName),
        ];

        // Optional files
        if (!empty($options['include_observer'])) {
            $filesContent["app/Domains/{$moduleName}/Observers/{$modelName}Observer.php"] = $this->generateObserverContent($moduleName, $modelName);
        }

        if (!empty($options['include_notification'])) {
            $filesContent["app/Domains/{$moduleName}/Notifications/{$modelName}Notification.php"] = $this->generateNotificationContent($moduleName, $modelName);
        }

        if (!empty($options['include_seeder'])) {
            $filesContent["app/Domains/{$moduleName}/Seeders/{$modelName}Seeder.php"] = $this->generateSeederContent($moduleName, $modelName);
        }

        if (!empty($options['include_factory'])) {
            $filesContent["app/Domains/{$moduleName}/Factories/{$modelName}Factory.php"] = $this->generateFactoryContent($moduleName, $modelName, $columns);
        }

        if (!empty($options['include_tests'])) {
            $filesContent["app/Domains/{$moduleName}/Tests/Unit/{$modelName}UnitTest.php"] = $this->generateUnitTestContent($moduleName, $modelName);
            $filesContent["app/Domains/{$moduleName}/Tests/Feature/{$modelName}FeatureTest.php"] = $this->generateFeatureTestContent($moduleName, $modelName);
        }

        // Write files to `codegenerator/{ModuleName}/` directory
        $generatedFiles = [];
        foreach ($filesContent as $relativePath => $content) {
            $fullPath = "{$outputDir}/{$relativePath}";
            $dir = dirname($fullPath);

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            File::put($fullPath, $content);
            $generatedFiles[] = $relativePath;
        }

        return [
            'output_directory' => $outputDir,
            'generated_files' => $generatedFiles,
            'files_content' => $filesContent,
        ];
    }

    protected function generateModelContent(string $domain, string $model, string $table, array $columns, array $relations): string
    {
        $fillables = [];
        $casts = [];

        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $fillables[] = "'{$name}'";

            $control = $col['control_type'] ?? 'text';
            if (in_array($control, ['switch_toggle', 'checkbox', 'boolean'], true)) {
                $casts[] = "'{$name}' => 'boolean'";
            } elseif (in_array($control, ['number', 'rating'], true)) {
                $casts[] = "'{$name}' => 'integer'";
            } elseif (in_array($control, ['decimal', 'currency'], true)) {
                $casts[] = "'{$name}' => 'decimal:2'";
            } elseif (in_array($control, ['json_editor', 'tags_input', 'multiple_file_upload', 'multiple_image_upload'], true)) {
                $casts[] = "'{$name}' => 'array'";
            } elseif (in_array($control, ['date'], true)) {
                $casts[] = "'{$name}' => 'date'";
            } elseif (in_array($control, ['datetime'], true)) {
                $casts[] = "'{$name}' => 'datetime'";
            }
        }

        $fillableStr = implode(",\n        ", $fillables);
        $castsStr = implode(",\n        ", $casts);

        // Relation methods code
        $relationMethods = [];
        foreach ($relations as $rel) {
            $relType = $rel['type'] ?? 'belongsTo';
            $relName = Str::camel($rel['name'] ?? 'relation');
            $targetModel = $rel['related_model'] ?? 'User';
            if (!Str::startsWith($targetModel, '\\') && !Str::contains($targetModel, '\\')) {
                $targetModel = "App\\Domains\\{$targetModel}\\Models\\{$targetModel}";
            }
            $foreignKey = $rel['foreign_key'] ?? 'user_id';
            $ownerKey = $rel['owner_key'] ?? 'id';
            $pivotTable = $rel['pivot_table'] ?? '';
            $throughModel = $rel['through_model'] ?? '';
            $morphName = $rel['morph_name'] ?? 'countable';

            $code = '';
            if ($relType === 'belongsTo') {
                $code = "\$this->belongsTo({$targetModel}::class, '{$foreignKey}', '{$ownerKey}')";
            } elseif ($relType === 'hasMany') {
                $code = "\$this->hasMany({$targetModel}::class, '{$foreignKey}', '{$ownerKey}')";
            } elseif ($relType === 'hasOne') {
                $code = "\$this->hasOne({$targetModel}::class, '{$foreignKey}', '{$ownerKey}')";
            } elseif ($relType === 'belongsToMany') {
                $pivotStr = !empty($pivotTable) ? "'{$pivotTable}', " : "";
                $code = "\$this->belongsToMany({$targetModel}::class, {$pivotStr}'{$foreignKey}', '{$ownerKey}')";
            } elseif (in_array($relType, ['hasOneThrough', 'hasManyThrough'], true)) {
                $code = "\$this->{$relType}({$targetModel}::class, {$throughModel}::class, '{$foreignKey}', '{$ownerKey}')";
            } elseif (in_array($relType, ['morphOne', 'morphMany', 'morphToMany', 'morphedByMany'], true)) {
                $code = "\$this->{$relType}({$targetModel}::class, '{$morphName}')";
            } elseif ($relType === 'morphTo') {
                $code = "\$this->morphTo()";
            } else {
                $code = "\$this->belongsTo({$targetModel}::class, '{$foreignKey}', '{$ownerKey}')";
            }

            $relationMethods[] = <<<PHP
    public function {$relName}(): \\Illuminate\\Database\\Eloquent\\Relations\\{$relType}
    {
        return {$code};
    }
PHP;
        }

        $relMethodsStr = implode("\n\n", $relationMethods);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$model} extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected \$table = '{$table}';

    protected \$fillable = [
        {$fillableStr}
    ];

    protected \$casts = [
        {$castsStr}
    ];

{$relMethodsStr}
}
PHP;
    }

    protected function generateCreateDtoContent(string $domain, string $model, array $columns): string
    {
        $props = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $nullable = !empty($col['nullable']) ? '?' : '';
            $props[] = "public {$nullable}mixed \${$name} = null";
        }
        $propsStr = implode(",\n        ", $props);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\DTOs;

use App\Shared\DTOs\BaseDTO;

class Create{$model}DTO extends BaseDTO
{
    public function __construct(
        {$propsStr}
    ) {}
}
PHP;
    }

    protected function generateUpdateDtoContent(string $domain, string $model, array $columns): string
    {
        $props = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $props[] = "public mixed \${$name} = null";
        }
        $propsStr = implode(",\n        ", $props);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\DTOs;

use App\Shared\DTOs\BaseDTO;

class Update{$model}DTO extends BaseDTO
{
    public function __construct(
        {$propsStr}
    ) {}
}
PHP;
    }

    protected function generateCreateRequestContent(string $domain, string $model, array $columns): string
    {
        $rules = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $ruleStr = $col['validation_rules'] ?? 'nullable|string';
            $rules[] = "'{$name}' => '{$ruleStr}'";
        }
        $rulesStr = implode(",\n            ", $rules);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Create{$model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            {$rulesStr}
        ];
    }
}
PHP;
    }

    protected function generateUpdateRequestContent(string $domain, string $model, array $columns): string
    {
        $rules = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $ruleStr = str_replace('required', 'sometimes', $col['validation_rules'] ?? 'nullable|string');
            $rules[] = "'{$name}' => '{$ruleStr}'";
        }
        $rulesStr = implode(",\n            ", $rules);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Update{$model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            {$rulesStr}
        ];
    }
}
PHP;
    }

    protected function generateCreateActionContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\DTOs\Create{$model}DTO;
use App\Domains\\{$domain}\Models\\{$model};
use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;

class Create{$model}Action
{
    public function __construct(
        protected {$model}RepositoryInterface \$repository
    ) {}

    public function execute(Create{$model}DTO \$dto): {$model}
    {
        /** @var {$model} */
        return \$this->repository->create(array_filter(\$dto->toArray(), fn(\$v) => \$v !== null));
    }
}
PHP;
    }

    protected function generateUpdateActionContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\DTOs\Update{$model}DTO;
use App\Domains\\{$domain}\Models\\{$model};
use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;

class Update{$model}Action
{
    public function __construct(
        protected {$model}RepositoryInterface \$repository
    ) {}

    public function execute(string \$id, Update{$model}DTO \$dto): {$model}
    {
        \$data = array_filter(\$dto->toArray(), fn(\$v) => \$v !== null);
        \$this->repository->update(\$id, \$data);
        return \$this->repository->findOrFail(\$id);
    }
}
PHP;
    }

    protected function generateDeleteActionContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;

class Delete{$model}Action
{
    public function __construct(
        protected {$model}RepositoryInterface \$repository
    ) {}

    public function execute(string \$id): bool
    {
        return \$this->repository->delete(\$id);
    }
}
PHP;
    }

    protected function generateBulkDeleteActionContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\Models\\{$model};

class BulkDelete{$model}Action
{
    public function execute(array \$ids): int
    {
        return {$model}::whereIn('id', \$ids)->delete();
    }
}
PHP;
    }

    protected function generateExportActionContent(string $domain, string $model, array $columns): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Actions;

use App\Domains\\{$domain}\Models\\{$model};

class Export{$model}Action
{
    public function execute(string \$format = 'csv'): array
    {
        \$records = {$model}::latest()->get();
        return \$records->toArray();
    }
}
PHP;
    }

    protected function generateRepositoryInterfaceContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Repositories\Contracts;

use App\Shared\Contracts\RepositoryInterface;

interface {$model}RepositoryInterface extends RepositoryInterface
{
    // Custom domain query contracts
}
PHP;
    }

    protected function generateRepositoryEloquentContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Repositories\Eloquent;

use App\Domains\\{$domain}\Models\\{$model};
use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;
use App\Shared\Services\BaseRepository;

class {$model}Repository extends BaseRepository implements {$model}RepositoryInterface
{
    protected function model(): string
    {
        return {$model}::class;
    }
}
PHP;
    }

    protected function generateServiceInterfaceContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Services\Contracts;

interface {$model}ServiceInterface
{
    // Business service contracts
}
PHP;
    }

    protected function generateServiceEloquentContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Services\Eloquent;

use App\Domains\\{$domain}\Services\Contracts\\{$model}ServiceInterface;

class {$model}Service implements {$model}ServiceInterface
{
    // Service orchestrations
}
PHP;
    }

    protected function generateResourceContent(string $domain, string $model, array $columns): string
    {
        $fields = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            $fields[] = "'{$name}' => \$this->{$name}";
        }
        $fieldsStr = implode(",\n            ", $fields);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$model}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return [
            {$fieldsStr}
        ];
    }
}
PHP;
    }

    protected function generateResourceCollectionContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class {$model}Collection extends ResourceCollection
{
    public function toArray(Request \$request): array
    {
        return [
            'data' => \$this->collection,
        ];
    }
}
PHP;
    }

    protected function generatePolicyContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Policies;

use App\Domains\User\Models\User;
use App\Domains\\{$domain}\Models\\{$model};

class {$model}Policy
{
    public function viewAny(User \$user): bool
    {
        return true;
    }

    public function view(User \$user, {$model} \$model): bool
    {
        return true;
    }

    public function create(User \$user): bool
    {
        return true;
    }

    public function update(User \$user, {$model} \$model): bool
    {
        return true;
    }

    public function delete(User \$user, {$model} \$model): bool
    {
        return true;
    }
}
PHP;
    }

    protected function generateServiceProviderContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain};

use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;
use App\Domains\\{$domain}\Repositories\Eloquent\\{$model}Repository;
use Illuminate\Support\ServiceProvider;

class {$domain}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \$this->app->bind(
            {$model}RepositoryInterface::class,
            {$model}Repository::class
        );
    }

    public function boot(): void
    {
        // Auto-bootstrap domain configurations
    }
}
PHP;
    }

    protected function generateControllerContent(string $domain, string $model, array $columns): string
    {
        $snake = Str::snake($domain);
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Http\Controllers;

use App\Domains\\{$domain}\Actions\Create{$model}Action;
use App\Domains\\{$domain}\Actions\Update{$model}Action;
use App\Domains\\{$domain}\Actions\Delete{$model}Action;
use App\Domains\\{$domain}\Actions\BulkDelete{$model}Action;
use App\Domains\\{$domain}\Actions\Export{$model}Action;
use App\Domains\\{$domain}\DTOs\Create{$model}DTO;
use App\Domains\\{$domain}\DTOs\Update{$model}DTO;
use App\Domains\\{$domain}\Models\\{$model};
use App\Domains\\{$domain}\Requests\Create{$model}Request;
use App\Domains\\{$domain}\Requests\Update{$model}Request;
use App\Domains\\{$domain}\Resources\\{$model}Resource;
use App\Domains\\{$domain}\Repositories\Contracts\\{$model}RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class {$model}Controller extends Controller
{
    public function __construct(
        protected {$model}RepositoryInterface \$repository
    ) {}

    public function index(Request \$request): View|JsonResponse
    {
        if (\$request->wantsJson()) {
            \$records = {$model}::latest()->paginate(\$request->input('per_page', 15));
            return response()->json([
                'status' => 'success',
                'data' => {$model}Resource::collection(\$records),
                'meta' => [
                    'current_page' => \$records->currentPage(),
                    'last_page' => \$records->lastPage(),
                    'total' => \$records->total(),
                ]
            ]);
        }

        \$records = {$model}::latest()->paginate(15);
        return view('admin.{$snake}.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.{$snake}.create');
    }

    public function store(Create{$model}Request \$request, Create{$model}Action \$action): RedirectResponse|JsonResponse
    {
        \$dto = Create{$model}DTO::fromRequest(\$request);
        \$record = \$action->execute(\$dto);

        if (\$request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '{$model} created successfully.',
                'data' => new {$model}Resource(\$record)
            ], 201);
        }

        return redirect()->route('admin.{$snake}.index')->with('success', '{$model} created successfully.');
    }

    public function show(string \$id, Request \$request): View|JsonResponse
    {
        \$record = \$this->repository->findOrFail(\$id);
        if (\$request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => new {$model}Resource(\$record)
            ]);
        }

        return view('admin.{$snake}.show', compact('record'));
    }

    public function edit(string \$id): View
    {
        \$record = \$this->repository->findOrFail(\$id);
        return view('admin.{$snake}.edit', compact('record'));
    }

    public function update(Update{$model}Request \$request, string \$id, Update{$model}Action \$action): RedirectResponse|JsonResponse
    {
        \$dto = Update{$model}DTO::fromRequest(\$request);
        \$record = \$action->execute(\$id, \$dto);

        if (\$request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '{$model} updated successfully.',
                'data' => new {$model}Resource(\$record)
            ]);
        }

        return redirect()->route('admin.{$snake}.index')->with('success', '{$model} updated successfully.');
    }

    public function destroy(string \$id, Request \$request, Delete{$model}Action \$action): RedirectResponse|JsonResponse
    {
        \$action->execute(\$id);

        if (\$request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '{$model} deleted successfully.'
            ]);
        }

        return redirect()->route('admin.{$snake}.index')->with('success', '{$model} deleted successfully.');
    }

    public function bulkDestroy(Request \$request, BulkDelete{$model}Action \$action): JsonResponse
    {
        \$ids = (array)\$request->input('ids', []);
        \$count = \$action->execute(\$ids);
        return response()->json([
            'status' => 'success',
            'message' => "{\$count} records deleted successfully."
        ]);
    }

    public function export(Request \$request, Export{$model}Action \$action): JsonResponse
    {
        \$data = \$action->execute(\$request->input('format', 'csv'));
        return response()->json([
            'status' => 'success',
            'data' => \$data
        ]);
    }
}
PHP;
    }

    protected function generateEventContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Events;

use App\Domains\\{$domain}\Models\\{$model};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$model}Created
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public {$model} \$record
    ) {}
}
PHP;
    }

    protected function generateListenerContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Listeners;

use App\Domains\\{$domain}\Events\\{$model}Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class Log{$model}Activity implements ShouldQueue
{
    public function handle({$model}Created \$event): void
    {
        Log::info("{$model} created with ID: " . \$event->record->id);
    }
}
PHP;
    }

    protected function generateObserverContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Observers;

use App\Domains\\{$domain}\Models\\{$model};

class {$model}Observer
{
    public function created({$model} \$record): void {}
    public function updated({$model} \$record): void {}
    public function deleted({$model} \$record): void {}
}
PHP;
    }

    protected function generateNotificationContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class {$model}Notification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object \$notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('{$model} Alert')
            ->line('Your {$model} record has been processed.')
            ->action('View Records', url('/'));
    }
}
PHP;
    }

    protected function generateSeederContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Seeders;

use App\Domains\\{$domain}\Models\\{$model};
use Illuminate\Database\Seeder;

class {$model}Seeder extends Seeder
{
    public function run(): void
    {
        {$model}::factory()->count(20)->create();
    }
}
PHP;
    }

    protected function generateFactoryContent(string $domain, string $model, array $columns): string
    {
        $fakers = [];
        foreach ($columns as $col) {
            $name = $col['name'] ?? '';
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $control = $col['control_type'] ?? 'text';
            if ($control === 'email') {
                $fakers[] = "'{$name}' => \$this->faker->unique()->safeEmail()";
            } elseif ($control === 'phone') {
                $fakers[] = "'{$name}' => \$this->faker->phoneNumber()";
            } elseif ($control === 'url') {
                $fakers[] = "'{$name}' => \$this->faker->url()";
            } elseif ($control === 'number') {
                $fakers[] = "'{$name}' => \$this->faker->numberBetween(1, 100)";
            } elseif ($control === 'decimal' || $control === 'currency') {
                $fakers[] = "'{$name}' => \$this->faker->randomFloat(2, 10, 500)";
            } elseif ($control === 'switch_toggle' || $control === 'boolean') {
                $fakers[] = "'{$name}' => \$this->faker->boolean()";
            } elseif ($control === 'date') {
                $fakers[] = "'{$name}' => \$this->faker->date()";
            } elseif ($control === 'datetime') {
                $fakers[] = "'{$name}' => \$this->faker->dateTimeThisYear()->format('Y-m-d H:i:s')";
            } else {
                $fakers[] = "'{$name}' => \$this->faker->words(3, true)";
            }
        }
        $fakerStr = implode(",\n            ", $fakers);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Factories;

use App\Domains\\{$domain}\Models\\{$model};
use Illuminate\Database\Eloquent\Factories\Factory;

class {$model}Factory extends Factory
{
    protected \$model = {$model}::class;

    public function definition(): array
    {
        return [
            {$fakerStr}
        ];
    }
}
PHP;
    }

    protected function generateUnitTestContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Tests\Unit;

use Tests\TestCase;

class {$model}UnitTest extends TestCase
{
    public function test_example(): void
    {
        \$this->assertTrue(true);
    }
}
PHP;
    }

    protected function generateFeatureTestContent(string $domain, string $model): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\Domains\\{$domain}\Tests\Feature;

use Tests\TestCase;

class {$model}FeatureTest extends TestCase
{
    public function test_index_returns_success(): void
    {
        \$response = \$this->getJson("/api/v1/" . strtolower("{$domain}"));
        \$response->assertStatus(200);
    }
}
PHP;
    }

    protected function generateIndexViewContent(string $domain, string $model, array $columns): string
    {
        $title = Str::headline($domain);
        $snake = Str::snake($domain);
        
        $tableCols = [];
        foreach ($columns as $col) {
            if (!empty($col['show_in_list'])) {
                $k = $col['name'];
                $l = $col['label'];
                $s = !empty($col['sortable']) ? 'true' : 'false';
                $tableCols[] = "{ key: '{$k}', label: '{$l}', sortable: {$s} }";
            }
        }
        $tableCols[] = "{ key: 'actions', label: 'Actions', sortable: false, class: 'text-right' }";
        $colsJs = implode(",\n      ", $tableCols);

        return <<<BLADE
@extends('admin.layouts.app')

@section('title', 'LaraforgeX — {$title} Module')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">{$title}</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-bold text-slate-900">{$title} Management</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Manage, search, filter and export {$title} records.</p>
    </div>
    <button id="btn-create-record"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs transition shadow-sm shadow-brand-600/20">
      <i class="fa-solid fa-plus"></i> Add New {$model}
    </button>
  </div>

  <div id="datatable-{$snake}"></div>
</div>

<!-- Modal Form -->
<div id="modal-{$snake}" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-{$snake}" class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-lg p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="record-id">
      <h3 class="font-bold text-lg text-slate-900" id="modal-title">Create {$model}</h3>
      <div class="space-y-4" id="form-fields-container">
        <!-- Rendered controls -->
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-500 text-xs font-semibold">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 text-white text-xs font-bold">Save {$model}</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  const table = new AdminTable({
    container: '#datatable-{$snake}',
    columns: [
      {$colsJs}
    ],
    fetch: async () => {
      const res = await axios.get('/{$snake}');
      return res.data.data;
    },
    row: (record) => `
      <tr class="hover:bg-slate-50/60 transition">
        <td class="px-5 py-4 text-sm font-semibold text-slate-900">\${record.id}</td>
        <td class="px-5 py-4 text-right">
          <button onclick="editRecord('\${record.id}')" class="px-2.5 py-1 rounded bg-white border border-slate-200 text-xs font-semibold">Edit</button>
          <button onclick="deleteRecord('\${record.id}')" class="px-2.5 py-1 rounded bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold">Delete</button>
        </td>
      </tr>
    `
  });
  table.load();
});
</script>
@endsection
BLADE;
    }

    protected function buildFormFieldHtml(array $col, bool $isEdit = false): string
    {
        $name = $col['name'];
        if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
            return '';
        }

        $label = $col['label'] ?? Str::headline($name);
        $control = $col['control_type'] ?? 'text';
        $placeholder = $col['placeholder'] ?? "Enter {$label}";
        $required = !empty($col['required']) ? 'required' : '';
        $valExpr = $isEdit ? "old('{$name}', \$record->{$name} ?? '')" : "old('{$name}', '')";
        $checkedExpr = $isEdit ? "old('{$name}', \$record->{$name} ?? false) ? 'checked' : ''" : "old('{$name}') ? 'checked' : ''";

        $inputHtml = '';
        if ($control === 'textarea' || $control === 'rich_text') {
            $inputHtml = "<textarea name=\"{$name}\" id=\"input-{$name}\" rows=\"3\" class=\"w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition\" placeholder=\"{$placeholder}\" {$required}>{{ {$valExpr} }}</textarea>";
        } elseif ($control === 'switch_toggle' || $control === 'boolean') {
            $inputHtml = "
          <label class=\"inline-flex items-center gap-2 cursor-pointer mt-1\">
            <input type=\"checkbox\" name=\"{$name}\" value=\"1\" {{ {$checkedExpr} }} class=\"rounded border-slate-300 text-brand-600 focus:ring-brand-500 h-4 w-4\">
            <span class=\"text-xs font-semibold text-slate-700\">Enable {$label}</span>
          </label>";
        } elseif ($control === 'select' || $control === 'single_select') {
            $inputHtml = "
          <select name=\"{$name}\" id=\"input-{$name}\" class=\"w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition\" {$required}>
            <option value=\"\">-- Select {$label} --</option>
            <option value=\"active\" {{ {$valExpr} == 'active' ? 'selected' : '' }}>Active</option>
            <option value=\"inactive\" {{ {$valExpr} == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>";
        } elseif ($control === 'date') {
            $inputHtml = "<input type=\"date\" name=\"{$name}\" id=\"input-{$name}\" value=\"{{ {$valExpr} }}\" class=\"w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition\" {$required}>";
        } elseif ($control === 'datetime') {
            $inputHtml = "<input type=\"datetime-local\" name=\"{$name}\" id=\"input-{$name}\" value=\"{{ {$valExpr} }}\" class=\"w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition\" {$required}>";
        } else {
            $type = in_array($control, ['number', 'decimal', 'currency']) ? 'number' : ($control === 'email' ? 'email' : ($control === 'password' ? 'password' : 'text'));
            $inputHtml = "<input type=\"{$type}\" name=\"{$name}\" id=\"input-{$name}\" value=\"{{ {$valExpr} }}\" class=\"w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition\" placeholder=\"{$placeholder}\" {$required}>";
        }

        $reqBadge = !empty($col['required']) ? '<span class="text-rose-500">*</span>' : '';

        return <<<HTML
        <div class="space-y-1">
          <label for="input-{$name}" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            {$label} {$reqBadge}
          </label>
          {$inputHtml}
          @error('{$name}')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ \$message }}</p>
          @enderror
        </div>
HTML;
    }

    protected function generateCreateViewContent(string $domain, string $model, array $columns): string
    {
        $title = Str::headline($domain);
        $snake = Str::snake($domain);

        $fields = [];
        foreach ($columns as $col) {
            if (!empty($col['show_in_create'])) {
                $html = $this->buildFormFieldHtml($col, false);
                if ($html) {
                    $fields[] = $html;
                }
            }
        }
        $fieldsHtml = implode("\n", $fields);

        return <<<BLADE
@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Create {$title}')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/{$snake}" class="hover:text-brand-600 transition">{$title}</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Create {$model}</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans w-full">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Create {$model}</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Add a new record to {$title}.</p>
    </div>
    <a href="/admin/{$snake}" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- Profile Style Card Container -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    
    <!-- Cover gradient header bar -->
    <div class="h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Header Avatar / Icon Badge -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-10 mb-4">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-2xl uppercase shrink-0">
          <i class="fa-solid fa-plus"></i>
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">New {$model} Entry</h3>
        <p class="text-xs font-semibold text-slate-500">Fill in attribute details below to create a record.</p>
      </div>

      <!-- Form Body -->
      <form action="/admin/{$snake}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
{$fieldsHtml}
        </div>

        <div class="flex justify-end pt-3">
          <button type="submit"
                  class="px-6 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
            <i class="fa-solid fa-floppy-disk"></i>Save {$model}
          </button>
        </div>
      </form>
    </div>

  </div>

</div>
@endsection
BLADE;
    }

    protected function generateEditViewContent(string $domain, string $model, array $columns): string
    {
        $title = Str::headline($domain);
        $snake = Str::snake($domain);

        $fields = [];
        foreach ($columns as $col) {
            if (!empty($col['show_in_edit'])) {
                $html = $this->buildFormFieldHtml($col, true);
                if ($html) {
                    $fields[] = $html;
                }
            }
        }
        $fieldsHtml = implode("\n", $fields);

        return <<<BLADE
@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Edit {$title}')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/{$snake}" class="hover:text-brand-600 transition">{$title}</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Edit #{{ \$record->id }}</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans w-full">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Edit {$model}</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Update record details for ID #{{ \$record->id }}.</p>
    </div>
    <a href="/admin/{$snake}" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- Profile Style Card Container -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    
    <!-- Cover gradient header bar -->
    <div class="h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Header Avatar / Icon Badge -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-10 mb-4">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-2xl uppercase shrink-0">
          <i class="fa-solid fa-pen"></i>
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{$model} Record #{{ \$record->id }}</h3>
        <p class="text-xs font-semibold text-slate-500">Modify attributes and save changes.</p>
      </div>

      <!-- Form Body -->
      <form action="/admin/{$snake}/{{ \$record->id }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
{$fieldsHtml}
        </div>

        <div class="flex justify-end pt-3">
          <button type="submit"
                  class="px-6 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
            <i class="fa-solid fa-floppy-disk"></i>Save Changes
          </button>
        </div>
      </form>
    </div>

  </div>

</div>
@endsection
BLADE;
    }

    protected function generateShowViewContent(string $domain, string $model, array $columns): string
    {
        $title = Str::headline($domain);
        $snake = Str::snake($domain);

        $detailCards = [];
        foreach ($columns as $col) {
            if (!empty($col['show_in_detail'])) {
                $name = $col['name'];
                $label = $col['label'] ?? Str::headline($name);
                $detailCards[] = "
        <div class=\"p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-0.5\">
          <p class=\"text-[10px] font-bold uppercase tracking-widest text-slate-400\">{$label}</p>
          <p class=\"text-sm font-bold text-slate-900 font-mono break-all\">{{ \$record->{$name} ?? '—' }}</p>
        </div>";
            }
        }
        $detailHtml = implode("\n", $detailCards);

        return <<<BLADE
@extends('admin.layouts.app')

@section('title', 'LaraforgeX — {$title} Details')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/{$snake}" class="hover:text-brand-600 transition">{$title}</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Details #{{ \$record->id }}</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans max-w-3xl">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">{$model} Details</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Viewing record information for ID #{{ \$record->id }}.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/{$snake}" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left"></i> Back to List
      </a>
      <a href="/admin/{$snake}/{{ \$record->id }}/edit" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
        <i class="fa-solid fa-pen"></i> Edit Record
      </a>
    </div>
  </div>

  <!-- Profile Style View Card Container -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    
    <!-- Cover gradient header bar -->
    <div class="h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Header Avatar / Icon Badge -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-10 mb-4">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-2xl uppercase shrink-0">
          <i class="fa-solid fa-eye"></i>
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{$model} Record #{{ \$record->id }}</h3>
        <p class="text-xs font-semibold text-slate-500">Full record attributes breakdown.</p>
      </div>

      <!-- Security Stats Style Attribute Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
{$detailHtml}
      </div>
    </div>

  </div>

</div>
@endsection
BLADE;
    }

    protected function generateApiRouteSnippet(string $domain, string $model): string
    {
        $snake = Str::snake($domain);
        return <<<PHP
// Route declaration snippet for routes/api.php:
Route::prefix('v1')->group(function () {
    Route::apiResource('{$snake}', \\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class);
    Route::post('{$snake}/bulk-delete', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'bulkDestroy']);
    Route::post('{$snake}/export', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'export']);
});
PHP;
    }

    protected function generateWebRouteSnippet(string $domain, string $model): string
    {
        $snake = Str::snake($domain);
        return <<<PHP
// Route declaration snippet for routes/web.php:
Route::prefix('admin')->group(function () {
    Route::get('/{$snake}', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'index'])->name('admin.{$snake}.index');
    Route::get('/{$snake}/create', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'create'])->name('admin.{$snake}.create');
    Route::post('/{$snake}', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'store'])->name('admin.{$snake}.store');
    Route::get('/{$snake}/{id}', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'show'])->name('admin.{$snake}.show');
    Route::get('/{$snake}/{id}/edit', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'edit'])->name('admin.{$snake}.edit');
    Route::put('/{$snake}/{id}', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'update'])->name('admin.{$snake}.update');
    Route::delete('/{$snake}/{id}', [\\App\\Domains\\{$domain}\\Http\\Controllers\\{$model}Controller::class, 'destroy'])->name('admin.{$snake}.destroy');
});
PHP;
    }
}
