@tide
Feature: Fields for Landing Page content type

  Ensure that Landing Page content has the expected fields.

  @api @nosuggest @javascript
  Scenario: The content type has the expected fields (and labels where we can use them).
    Given I am logged in as a user with the "create landing_page content" permission
    # Given I am logged in as a user with the "editor" role
    When I visit "node/add/landing_page"
    And save screenshot
    Then I see field "Title"
    And I should see an "input#edit-title-0-value.required" element

    And I click on the horizontal tab "Header"
    And I check the box "Add separate introduction text"
    And I see field "Introduction text"
    And I should see an "textarea#edit-field-landing-page-intro-text-0-value" element
    And I should not see an "textarea#edit-field-landing-page-intro-text-0-value.required" element

    And I should see text matching "Header links"
    And I should see text matching "No Header link added yet."
    And I should see the button "Add Header link" in the "content" region

    And I click on the horizontal tab "Customised Header"

    And I select the radio button "Call to action banner"
    And I should see text matching "Hero banner"
    And I should see text matching "No Hero Banner with CTA added yet."
    And I should see the button "Add Hero banner with CTA" in the "content" region

    And the "#edit-field-landing-page-hero-logo" element should contain "Logo"
    And I should see an "input#edit-field-landing-page-hero-logo-entity-browser-entity-browser-open-modal" element

    And I select the radio button "Corner graphics"
    And the "#edit-field-bottom-graphical-image" element should contain "Bottom Corner Graphic"
    And I should see an "input#edit-field-bottom-graphical-image-entity-browser-target" element

    And I scroll selector "#edit-field-featured-image" into view
    And the "#edit-field-featured-image" element should contain "Feature Image"
    And I should see an "input#edit-field-featured-image-entity-browser-entity-browser-open-modal" element

    And I click on the horizontal tab "Page content"
    And I see field "Show Table of Content?"
    And I should see an "input#edit-field-show-table-of-content-value" element
    And I should not see an "input#edit-field-show-table-of-content.required" element

    When I check "edit-field-show-table-of-content-value"
    Then I should see text matching "Display headings"
    And I should see an "input#edit-field-node-display-headings-showh2" element
    And I should see an "input#edit-field-node-display-headings-showh2andh3" element

    And I should see text matching "CONTENT COMPONENTS"
    And I press the "edit-field-landing-page-component-add-more-add-modal-form-area-add-more" button
    And I should see the button "Basic text"
    And I should see the button "Accordion"
    And I should see the button "Call to action"
    And I should see the button "Card carousel"
    And I should see the button "Card event"
    And I should see the button "Card event automated"
    And I should see the button "Latest events"
    And I should see the button "Card promotion"
    And I should see the button "Navigation featured"
    And I should see the button "Navigation featured automated"
    And I should see the button "Navigation"
    And I should see the button "Navigation automated"
    And I should see the button "Key dates"
    And I should see the button "Image gallery"
    And I should see the button "Complex image"
    And I press the "Close" button

    And I click on the horizontal tab "Top page feature"
    And I should see text matching "Primary Campaign"
    And I should see an "input#edit-field-landing-page-c-primary-0-target-id" element
    And I should not see an "input#edit-field-landing-page-c-primary-0-target-id.required" element

    And I should see text matching "HEADER COMPONENTS"
    And I press the "edit-field-landing-page-header-add-more-add-modal-form-area-add-more" button
    And I should see the button "Introduction banner"
    And I should see the button "Embedded search form"
    And I press the "Close" button

    And I click on the horizontal tab "Bottom page feature"
    And I should see text matching "Secondary campaign"
    And I should see an "input#edit-field-landing-page-c-secondary-0-target-id" element
    And I should not see an "input#edit-field-landing-page-c-secondary-0-target-id.required" element

    And I click on the horizontal tab "Side bar"
    And I see field "Show Site-section Navigation?"
    And I should see an "input#edit-field-landing-page-nav-title-0-value" element
    And I should not see an "input#edit-field-landing-page-nav-title-0-value.required" element

    And I see field "Show Related Content?"
    And I should see an "input#edit-field-show-related-content-value" element
    And I should not see an "input#edit-field-show-related-content-value.required" element

    And I should see text matching "Related links"
    And I should see the button "Add Related links" in the "content" region

    And I should see text matching "Social sharing"

    And I should see text matching "What's Next"
    And I should see text matching "No What's Next block added yet."
    And I should see the button "Add Link" in the "content" region

    And I see field "Show what's next?"
    And I should see an "input#edit-field-show-whats-next-value" element
    And I should not see an "input#edit-field-show-whats-next-value.required" element

    And I see field "Show contact details"
    And I should see an "input#edit-field-landing-page-show-contact-value" element
    And I should not see an "input#edit-field-landing-page-show-contact-value.required" element

    And I should see text matching "Contact us"
    And I should see text matching "No Contact Us block added yet."
    And I should see the button "Add Contact Us" in the "content" region

    And I see field "Tags"
    And I should see an "input#edit-field-tags-0-target-id" element
    And I should not see an "input#edit-field-tags-0-target-id.required" element

    And I see field "Topic"
    And I should see an "input#edit-field-topic-0-target-id" element
    And I should see an "input#edit-field-topic-0-target-id.required" element

    And I see field "Show topic term and tags?"

    And I see field "Background colour"
    And I should see an "select#edit-field-landing-page-bg-colour" element
    And I should see an "select#edit-field-landing-page-bg-colour.required" element

  @api
  Scenario: The content type has the menu settings.
    Given I am logged in as a user with the "create landing_page content, administer menu" permission
    When I visit "node/add/landing_page"
    And I should see the text "Menu settings"
    And I see field "Provide a menu link"

  @api @suggest @javascript
  Scenario: The content type has the expected fields (and labels where we can use them) including from suggested modules.
    Given I am logged in as a user with the "create landing_page content" permission
    # Given I am logged in as a user with the "editor" role
    When I visit "node/add/landing_page"
    And save screenshot
    Then I see field "Title"
    And I should see an "input#edit-title-0-value.required" element

    And I click on the horizontal tab "Header"
    And I check the box "Add separate introduction text"
    And I see field "Introduction text"
    And I should see an "textarea#edit-field-landing-page-intro-text-0-value" element
    And I should not see an "textarea#edit-field-landing-page-intro-text-0-value.required" element

    And I should see text matching "Header links"
    And I should see text matching "No Header link added yet."
    And I should see the button "Add Header link" in the "content" region

    And I click on the horizontal tab "Customised Header"

    And I select the radio button "Call to action banner"
    And I should see text matching "Hero banner"
    And I should see text matching "No Hero Banner with CTA added yet."
    And I should see the button "Add Hero banner with CTA" in the "content" region

    And the "#edit-field-landing-page-hero-logo" element should contain "Logo"
    And I should see an "input#edit-field-landing-page-hero-logo-entity-browser-entity-browser-open-modal" element

    And I select the radio button "Corner graphics"
    And the "#edit-field-bottom-graphical-image" element should contain "Bottom Corner Graphic"
    And I should see an "input#edit-field-bottom-graphical-image-entity-browser-target" element

    And I scroll selector "#edit-field-featured-image" into view
    And the "#edit-field-featured-image" element should contain "Feature Image"
    And I should see an "input#edit-field-featured-image-entity-browser-entity-browser-open-modal" element

    And I click on the horizontal tab "Page content"
    And I see field "Show Table of Content?"
    And I should see an "input#edit-field-show-table-of-content-value" element
    And I should not see an "input#edit-field-show-table-of-content.required" element

    When I check "edit-field-show-table-of-content-value"
    Then I should see text matching "Display headings"
    And I should see an "input#edit-field-node-display-headings-showh2" element
    And I should see an "input#edit-field-node-display-headings-showh2andh3" element

    And I should see text matching "CONTENT COMPONENTS"
    And I press the "edit-field-landing-page-component-add-more-add-modal-form-area-add-more" button
    And I should see the button "Basic text"
    And I should see the button "Accordion"
    And I should see the button "Call to action"
    And I should see the button "Card carousel"
    And I should see the button "Card event"
    And I should see the button "Card event automated"
    And I should see the button "Latest events"
    And I should see the button "Card promotion"
    And I should see the button "Navigation featured"
    And I should see the button "Navigation featured automated"
    And I should see the button "Navigation"
    And I should see the button "Navigation automated"
    And I should see the button "Key dates"
    And I should see the button "Image gallery"
    And I should see the button "Complex image"

    And I should see the button "Form embed (Drupal)"
    And I should see the button "Form embed (OpenForms)"
    And I should see the button "Featured news"

    And I press the "Close" button

    And I click on the horizontal tab "Top page feature"
    And I should see text matching "Primary Campaign"
    And I should see an "input#edit-field-landing-page-c-primary-0-target-id" element
    And I should not see an "input#edit-field-landing-page-c-primary-0-target-id.required" element

    And I should see text matching "HEADER COMPONENTS"
    And I press the "edit-field-landing-page-header-add-more-add-modal-form-area-add-more" button
    And I should see the button "Introduction banner"
    And I should see the button "Embedded search form"
    And I press the "Close" button

    And I click on the horizontal tab "Bottom page feature"
    And I should see text matching "Secondary campaign"
    And I should see an "input#edit-field-landing-page-c-secondary-0-target-id" element
    And I should not see an "input#edit-field-landing-page-c-secondary-0-target-id.required" element

    And I click on the horizontal tab "Side bar"
    And I see field "Show Site-section Navigation?"
    And I should see an "input#edit-field-landing-page-nav-title-0-value" element
    And I should not see an "input#edit-field-landing-page-nav-title-0-value.required" element

    And I see field "Show Related Content?"
    And I should see an "input#edit-field-show-related-content-value" element
    And I should not see an "input#edit-field-show-related-content-value.required" element

    And I should see text matching "Related links"
    And I should see the button "Add Related links" in the "content" region

    And I should see text matching "Social sharing"

    And I should see text matching "What's Next"
    And I should see text matching "No What's Next block added yet."
    And I should see the button "Add Link" in the "content" region

    And I see field "Show what's next?"
    And I should see an "input#edit-field-show-whats-next-value" element
    And I should not see an "input#edit-field-show-whats-next-value.required" element

    And I see field "Show contact details"
    And I should see an "input#edit-field-landing-page-show-contact-value" element
    And I should not see an "input#edit-field-landing-page-show-contact-value.required" element

    And I should see text matching "Contact us"
    And I should see text matching "No Contact Us block added yet."
    And I should see the button "Add Contact Us" in the "content" region

    And I see field "Tags"
    And I should see an "input#edit-field-tags-0-target-id" element
    And I should not see an "input#edit-field-tags-0-target-id.required" element

    And I see field "Topic"
    And I should see an "input#edit-field-topic-0-target-id" element
    And I should see an "input#edit-field-topic-0-target-id.required" element

    And I see field "Show topic term and tags?"

    And I see field "Background colour"
    And I should see an "select#edit-field-landing-page-bg-colour" element
    And I should see an "select#edit-field-landing-page-bg-colour.required" element
