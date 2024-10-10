<?php

declare(strict_types=1);

namespace AspirePress\AspireCloud\Data\Repositories;

use AspirePress\AspireCloud\Data\Entities\DownloadableFile;
use AspirePress\AspireCloud\Data\Entities\Plugin;
use Aura\Sql\ExtendedPdoInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class PluginRepository
{
    public function __construct(private ExtendedPdoInterface $epdo)
    {
    }

    public function getPluginBySlug(string $slug): ?Plugin
    {
        if (empty($slug)) {
            return null;
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('plugins')
            ->where('slug = :slug')
            ->setParameter(':slug', $slug);

        $data = $queryBuilder->executeQuery()->fetchAssociative();

        if (! $data) {
            return null;
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('files')
            ->where('plugin_id = :plugin_id')
            ->andWhere('version = :version')
            ->andWhere("type = 'cdn'")
            ->setParameter(':plugin_id', $data['id'])
            ->setParameter(':version', $data['current_version']);

        $fileData = $queryBuilder->executeQuery()->fetchAssociative();
        
        if ($fileData) {
            $file         = DownloadableFile::fromArray($fileData);
            $data['file'] = $file;
        }

        return Plugin::fromArray($data);
    }
}
