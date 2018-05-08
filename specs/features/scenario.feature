Feature: Resolve Scenario
  Resolve a complet scenario

  Scenario: Create a biscuit
    Given the "simpleCookie" bible
    When I play "smallCookie" Scenario
    Then I should have a "cookie shape" value
    And I should have a "cookie flavor" value
    And I should have a "cookie word" value
