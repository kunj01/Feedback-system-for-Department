<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\IOFactory;

class ExtractFormContent extends Command
{
    protected $signature = 'form:extract {filename}';
    protected $description = 'Extract content from a Word document form';

    public function handle()
    {
        $filename = $this->argument('filename');
        $filePath = public_path('documents/' . $filename);

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        try {
            $phpWord = IOFactory::load($filePath);
            $this->info("=== DOCUMENT CONTENT ===\n");

            foreach ($phpWord->getSections() as $sectionIndex => $section) {
                $this->info("Section {$sectionIndex}:");
                
                foreach ($section->getElements() as $element) {
                    $this->extractText($element, 0);
                }
            }

            $this->info("\n=== END OF DOCUMENT ===");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error reading document: " . $e->getMessage());
            return 1;
        }
    }

    private function extractText($element, $indent = 0)
    {
        $prefix = str_repeat("  ", $indent);
        
        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            foreach ($element->getElements() as $textElement) {
                if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                    $text = $textElement->getText();
                    if (!empty(trim($text))) {
                        $this->line($prefix . $text);
                    }
                }
            }
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $text = $element->getText();
            if (!empty(trim($text))) {
                $this->line($prefix . $text);
            }
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
            $this->line($prefix . "[TABLE]");
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    foreach ($cell->getElements() as $cellElement) {
                        $this->extractText($cellElement, $indent + 1);
                    }
                }
            }
        } elseif (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $this->extractText($childElement, $indent + 1);
            }
        }
    }
}
