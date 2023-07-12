<?php

namespace KimaiPlugin\UserPreferenceBundle\EventSubscriber;

use App\Entity\UserPreference;
use App\Event\UserPreferenceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @see https://github.com/Keleo/DemoBundle/blob/main/EventSubscriber/UserPreferenceSubscriber.php
 */
final class SetUserPreferenceSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserPreferenceEvent::class => ['onUserPreferenceEvent', 200],
        ];
    }

    public function onUserPreferenceEvent(UserPreferenceEvent $event)
    {
        $preferences = [];

        //work hours
        $workhours = [UserPreference::WORK_HOURS_MONDAY, UserPreference::WORK_HOURS_TUESDAY, UserPreference::WORK_HOURS_WEDNESDAY, UserPreference::WORK_HOURS_THURSDAY, UserPreference::WORK_HOURS_FRIDAY, UserPreference::WORK_HOURS_SATURDAY, UserPreference::WORK_HOURS_SUNDAY,];
        foreach ($workhours as $workhour) {
            $preferences[] =
                (new UserPreference($workhour, 0))
                    ->setSection('workhours')
                    ->setType(NumberType::class)
                    ->setOptions(["html5" => true])
                    ->addConstraint(new Range(['min' => 0]));
        }

        //holiday
        $preferences[] =
            (new UserPreference(UserPreference::HOLIDAYS_PER_YEAR, 0))
                ->setSection('holidays')
                ->setType(NumberType::class)
                ->setOptions(["html5" => true])
                ->addConstraint(new Range(['min' => 0]));


//        $preferences[] =
//            (new UserPreference("test"))
//                ->setSection('test')
//                ->setType(TextType::class);

        foreach ($preferences as $preference) {
            $event->addPreference($preference);
        }
    }
}
