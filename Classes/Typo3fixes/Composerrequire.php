<?php

namespace SUDHAUS7\Sudhaus7Base\Typo3fixes;

class Composerrequire extends \TYPO3\CMS\Core\Package\DependencyResolver {
    /**
     * Convert the package configuration into a dependency definition
     *
     * This converts "dependencies" and "suggestions" to "after" syntax for the usage in DependencyOrderingService
     *
     * @param array $packageStatesConfiguration
     * @param array $packageKeys
     * @return array
     * @throws \UnexpectedValueException
     */
    protected function convertConfigurationForGraph(array $packageStatesConfiguration, array $packageKeys)
    {
        $dependencies = [];
        foreach ($packageKeys as $packageKey) {
            if (!isset($packageStatesConfiguration[$packageKey]['dependencies']) && !isset($packageStatesConfiguration[$packageKey]['suggestions'])) {
                continue;
            }
            $dependencies[$packageKey] = [
                'after' => []
            ];
            if (isset($packageStatesConfiguration[$packageKey]['dependencies'])) {
                foreach ($packageStatesConfiguration[$packageKey]['dependencies'] as $dependentPackageKey) {
                    if (!in_array($dependentPackageKey, $packageKeys, true)) {
                        /* throw new \UnexpectedValueException(
                            'The package "' . $packageKey . '" depends on "'
                            . $dependentPackageKey . '" which is not present in the system.',
                            1382276561);*/
                        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array($dependentPackageKey,$packageKeys));
                        continue;
                    }
                    $dependencies[$packageKey]['after'][] = $dependentPackageKey;
                }
            }
            if (isset($packageStatesConfiguration[$packageKey]['suggestions'])) {
                foreach ($packageStatesConfiguration[$packageKey]['suggestions'] as $suggestedPackageKey) {
                    // skip suggestions on not existing packages
                    if (in_array($suggestedPackageKey, $packageKeys, true)) {
                        // Suggestions actually have never been meant to influence loading order.
                        // We misuse this currently, as there is no other way to influence the loading order
                        // for not-required packages (soft-dependency).
                        // When considering suggestions for the loading order, we might create a cyclic dependency
                        // if the suggested package already has a real dependency on this package, so the suggestion
                        // has do be dropped in this case and must *not* be taken into account for loading order evaluation.
                        $dependencies[$packageKey]['after-resilient'][] = $suggestedPackageKey;
                    }
                }
            }
        }
        return $dependencies;
    }
}
