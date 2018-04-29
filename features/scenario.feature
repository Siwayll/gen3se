Feature: Resolve Scenario
  Resolve a complet scenario

  Scenario: Create a biscuit
    Given the "biscuit" bible
    When I play "oneBiscuit" Scenario
    Then I should have a biscuit
