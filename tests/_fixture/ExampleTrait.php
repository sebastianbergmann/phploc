<?php declare(strict_types=1);
/*
 * This file is part of sebastian/complexity.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Complexity\TestFixture;

trait ExampleTrait
{
    public function method(): void
    {
        if (true || false) {
            if (true && false) {
                for ($i = 0; $i <= 1; $i++) {
                    $a = true ? 'foo' : 'bar';
                }

                foreach (range(0, 1) as $i) {
                    switch ($i) {
                        case 0:
                            break;

                        case 1:
                            break;

                        default:
                    }
                }
            }
        } elseif (null) {
            try {
            } catch (Throwable $t) {
            }
        }

        while (true) {
        }

        do {
        } while (false);
    }
}
