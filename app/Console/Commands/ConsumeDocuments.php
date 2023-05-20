<?php

namespace App\Console\Commands;

use App\Models\Document;
use File;
use Illuminate\Console\Command;
use Mindwave\Mindwave\Facades\DocumentLoader;
use Mindwave\Mindwave\Facades\Mindwave;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class ConsumeDocuments extends Command
{
    protected $signature = 'documents:consume {folder}';

    protected $description = 'Consume all files within a folder and puts them in the database';

    public function handle()
    {
        $folder = $this->argument('folder');

        $files = Finder::create()->files()->in($folder);

        if (! $files) {
            $this->error("No files found in $folder");
        }

        $this->withProgressBar($files, fn (SplFileInfo $file) => $this->consumeFile($file));
    }

    protected function consumeFile(SplFileInfo $file)
    {
        $model = Document::create([
            'filename' => $file->getFilename(),
            'type' => $file->getExtension(),
        ]);

        $doc = DocumentLoader::load(
            data: File::get($file->getRealPath()),
            meta: ['id' => $model->id]
        );

        if ($doc->isNotEmpty()) {
            $model->update([
                'text' => $doc->content(),
                'state' => 'consuming',
            ]);

            Mindwave::brain()->consume($doc);

            $model->update(['state' => 'consumed']);
        } else {
            $model->update(['state' => 'empty']);
        }
    }
}
