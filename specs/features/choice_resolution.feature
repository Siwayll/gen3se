Feature: Resolve Choices
  Resolve a Choice

  Background:
    Given the "color" Choice
        | name   | weight |
        | red    | 100    |
        | blue   | 600    |
        | green  | 100    |
        | purple | 100    |

  @happyPath
  Scenario: Chose blue
    Given The randomizer blocked to 200
    When I resolve it
    Then I should have 1 Option on result export to it
    And I should have "blue" as result to color

  @happyPath
  Scenario: Randomizer send maximum value
    Given The randomizer blocked to 900
    When I resolve it
    Then I should have 1 Option on result export to it
    And I should have "purple" as result to color

  @happyPath
  Scenario: Randomizer send minimum value
    Given The randomizer blocked to 0
    When I resolve it
    Then I should have 1 Option on result export to it
    And I should have "red" as result to color

  @greedyPath
  Scenario: Value send by randomizer is too high
    Given The randomizer blocked to 9999
    When I resolve it
    Then I should have 0 Option on result export to it

  @greedyPath
  Scenario: Value send by randomizer is under zero
    Given The randomizer blocked to -200
    When I resolve it
    Then I should have 0 Option on result export to it
