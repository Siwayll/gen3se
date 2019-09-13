Feature: Add custom data to Choice
    Add anykind of Data to a Choice and retrieve it.

  Background:
    Given the "shape" Choice
        | name     | weight |
        | circle   | 100    |
        | triangle | 100    |
        | square   | 100    |

  @happyPath @d_data
  Scenario: Add a data
    Given The Data "custom" "value" with code "cusD"
    When I add the Data "cusD" to "shape"
    Then I should have "cusD" Data when export data from "shape"
    And Exported "cusD" from "shape" should have to value
    """
    a:1:{s:6:"custom";s:6:"values";}
    """


