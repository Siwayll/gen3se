Feature: Resolve Scenario
  Resolve a complet scenario

  Scenario: Create a biscuit
    Given the "biscuit" gen3rator
    When I execute Gen3se
    Then I should have a biscuit
