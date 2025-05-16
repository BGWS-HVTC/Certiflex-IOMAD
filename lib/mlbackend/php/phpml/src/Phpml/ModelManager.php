<?php

declare(strict_types=1);

namespace Phpml;

use Phpml\Exception\FileException;
use Phpml\Exception\SerializeException;
use Phpml\Classification\MLPClassifier;

class ModelManager
{
    public function saveToFile(Estimator $estimator, string $filepath): void
    {
        if (!is_writable(dirname($filepath))) {
            throw new FileException(sprintf('File "%s" cannot be saved.', basename($filepath)));
        }

        $serialized = serialize($estimator);
        if (!isset($serialized[0])) {
            throw new SerializeException(sprintf('Class "%s" cannot be serialized.', gettype($estimator)));
        }

        $result = file_put_contents($filepath, $serialized, LOCK_EX);
        if ($result === false) {
            throw new FileException(sprintf('File "%s" cannot be saved.', basename($filepath)));
        }
    }

    public function restoreFromFile(string $filepath): Estimator
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw new FileException(sprintf('File "%s" cannot be opened.', basename($filepath)));
        }

        // BGWS Modification START
        // Author - Mike Robb
        // Jira ticket - CER-56
        $contents = file_get_contents($filepath);
        // Check the top level objects are valid and safe allowed classes
        $object = unserialize((string) $contents, ['allowed_classes' => [Pipeline::class, MLPClassifier::class]]);
        if ($object === false || !$object instanceof Estimator) {
            throw new SerializeException(sprintf('"%s" cannot be unserialized.', basename($filepath)));
        }
        // If the initial unserialize check passes our two allowed classes, unserialize fully unrestricted
        $object = unserialize((string)$contents);
        // BGWS Modification END

        return $object;
    }
}
