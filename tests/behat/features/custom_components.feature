Feature: Check the Custom components taxonomy

  Ensure Custom components vocabulary exist.

  @api
  Scenario: Custom components taxonomy vocabulary exists
    Given vocabulary "custom_components" with name "Custom components" exists
    When I am logged in as a user with the "Site Admin" role
    And I go to "admin/structure/taxonomy/manage/custom_components/add"
    Then I see field "Name"
    And I should see an "input#edit-name-0-value.required" element
    And I see field "Machine name"
    And I should see an "input#edit-field-machine-name-0-value.required" element
    And I see field "Default options"
    And I should not see an "input#edit-field-default-options-0-value.required" element
    And I don't see field "Description"

  @api @nosuggest
  Scenario: The content type has the expected valid fields (Custom Component) JSON Validation.
    Given I am logged in as a user with the "site_admin" role
    When I visit "admin/structure/taxonomy/manage/custom_components/add"
    And I fill in the following:
      | name[0][value]                  | Testing title 1                     |
      | field_machine_name[0][value]    | machine_name                        |
      | field_default_options[0][value] | [{"user_id":13,"username":"stack"}] |
    And I press "Save"
    And I wait for 2 seconds
    And I should see text matching "Testing title 1"

  @api @nosuggest
  Scenario: The content type has the expected invalid fields (Custom Component) JSON Validation.
    Given I am logged in as a user with the "site_admin" role
    When I visit "admin/structure/taxonomy/manage/custom_components/add"
    And I fill in the following:
      | name[0][value]                  | Testing title   |
      | field_machine_name[0][value]    | machine_name    |
      | field_default_options[0][value] | test wrong json |
    And I press "Save"
    And I wait for 2 seconds
    And I should see text matching "The Default options field should contain a valid JSON string."
