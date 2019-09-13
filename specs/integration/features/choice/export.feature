Feature: Export data
    A choice can export its name, its data and all its results

  Background:
    Given the "shape" Choice
        | name     | weight |
        | circle   | 100    |
        | triangle | 100    |
        | square   | 100    |

  @happyPath
  Scenario: Export Name
    When I resolve it
    Then I should have a right name when export data from shape

  @happyPath
  Scenario: Export Option
    When I resolve it
    Then I should have 1 Option on result export to it

  @happyPath
  Scenario: Export Option
    When I resolve it 3 times
    Then I should have 3 Option on result export to it
