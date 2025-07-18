<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoUrlValidator extends ConstraintValidator
{
    public const SRC_REGEX = '#<iframe[^>]+src="(https?://(?:www\.)?(?:youtube\.com/embed/|player\.vimeo\.com/video/|www\.dailymotion\.com/embed/video/)[^"]+)"[^>]*></iframe>#';
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof VideoUrl) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        // Récupérer l'objet Video en cours de validation
        $object = $this->context->getObject();

        // Si c'est un objet Video et que skipValidation est true, on saute la validation
        if ($object && method_exists($object, 'isSkipValidation') && $object->isSkipValidation()) {
            return;
        }

        // Vérifie la présence d'une balise iframe avec un src valide
        if (!preg_match(self::SRC_REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('url')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
