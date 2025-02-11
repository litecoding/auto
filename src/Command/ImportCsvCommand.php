<?php

namespace App\Command;

use DateTime;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'app:import-csv',
    description: 'Import from CSV file',
)]
class ImportCsvCommand extends Command
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = $this->getApplication()->getKernel()->getProjectDir();
        $filePath = $projectDir . '/var/upload/data.csv';

        if (!file_exists($filePath)) {
            $io->error('Файл не знайдено: ' . $filePath);
            return Command::FAILURE;
        }

        $batchSize = 100;
        $processedRows = 0;

        $file = new \SplFileObject($filePath);
        $file->setFlags(\SplFileObject::READ_CSV);
        $file->setCsvControl(';', '"');

        $file->fgetcsv();

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $this->connection->beginTransaction();

        $values = [];
        while (($data = $file->fgetcsv()) !== false) {
            if (empty($data[0]) || count($data) < 20) {
                continue;
            }

            $values[] = [
                'person' => $data[0],
                'reg_addr_koatuu' => (int)$data[1],
                'oper_code' => (int)$data[2],
                'oper_name' => $data[3],
                'd_reg' => DateTime::createFromFormat('d.m.y', $data[4])->format('Y-m-d'),
                'dep_code' => (int)$data[5],
                'dep' => $data[6],
                'brand' => $data[7],
                'model' => $data[8],
                'vin' => $data[9],
                'make_year' => (int)$data[10],
                'color' => $data[11],
                'kind' => $data[12],
                'body' => $data[13],
                'purpose' => $data[14],
                'fuel' => $data[15],
                'capacity' => (float)$data[16] ?: null,
                'own_weight' => (int)$data[17],
                'total_weight' => (int)$data[18],
                'n_reg_new' => $data[19],
            ];

            $processedRows++;

            if ($processedRows % $batchSize === 0) {
                $this->insertBatch($values);
                $values = [];
            }

            $progressBar->advance();
        }

        if (count($values) > 0) {
            $this->insertBatch($values);
        }

        $this->connection->commit();

        $progressBar->finish();
        $io->newLine();
        $io->success('All data imported');

        return Command::SUCCESS;
    }

    private function insertBatch(array $values): void
    {
        $query = 'INSERT INTO vehicle (person, reg_addr_koatuu, oper_code, oper_name, d_reg, dep_code, dep, brand, model, vin, make_year, color, kind, body, purpose, fuel, capacity, own_weight, total_weight, n_reg_new) VALUES ';
        $placeholders = [];
        $params = [];

        foreach ($values as $value) {
            $placeholders[] = '(' . implode(',', array_fill(0, count($value), '?')) . ')';
            $params = array_merge($params, array_values($value));
        }

        $query .= implode(',', $placeholders);

        $this->connection->executeQuery($query, $params);
    }
}
