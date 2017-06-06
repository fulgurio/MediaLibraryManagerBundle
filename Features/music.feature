Feature: Manager music
  -----
  Route:
  - FulgurioMLM_Music_Add
  - FulgurioMLM_Music_Edit
  - FulgurioMLM_Music_Remove
  - FulgurioMLM_Music_List

  @database

  Scenario: Add new album
    Given I am on "/"
    When I follow the "Music" link
    Then I should be on "/music/"
    And I should see "No album"
    When I follow the "Add new album" link
    Then I should be on "/music/add"
    And I should see "Artist"
    And I should see "Album name"
    And I should see "Media type"
    And I should see "EAN"
    And I should see "Publication year"
    And I should see "Publisher"
    And I should see "Cover"
    And I should see "Tracks"
#    When I press the "Save" button
#    Then I should be on "/music/add"
#    And I should see "Album name is required"
    When I fill in the following:
      | music_album[artist] | Pink Floyd |
      | music_album[title] | Animas |
      | music_album[publication_year] | 1977 |
      | music_album[publisher] | EMI |
    And I press the "Save" button
    Then I should be on "/music/"
    And I should see "New album added successfully !"
    And I should see "Pink Floyd"
    And I should see "Animas"

  Scenario: Search album
    Given I am on "/music"
    Then I should see "Pink Floyd"
    When I fill in the following:
      | q | David |
    And I press the "Search" button
    Then I should see "No result found"
    When I fill in the following:
      | q | Pink |
    And I press the "Search" button
    Then I should see "Pink Floyd"
    When I fill in the following:
      | q | Anima |
    And I press the "Search" button
    Then I should see "Pink Floyd"

  Scenario: Edit album
    Given I am on "/music"
    Then I should see "Pink Floyd"
    And I should see "Animas"
    And I should not see "Animals"
    When I press the 1st "Edit" link
    Then I should be on "/music/1/edit"
    When I fill in the following:
      | music_album[title] | Animals |
    And I press the "Save" button
    Then I should be on "/music/"
    And I should see "Album edited successfully !"
    And I should see "Pink Floyd"
    And I should not see "Animas"
    And I should see "Animals"

  Scenario: Add tracks album
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    When I press "Add track"
    And I fill in the following:
      | music_album[tracks][0][title] | Pigs on the Wing 1 |
      | music_album[tracks][0][duration] | 85 |
    And I press "Add track"
    And I fill in the following:
      | music_album[tracks][1][title] | Dogs |
      | music_album[tracks][1][duration] | 1024 |
    And I press "Add track"
    And I press "Add track"
    And I press "Add track"
    And I fill in the following:
      | music_album[tracks][2][title] | Pigs (Three Different Ones) |
      | music_album[tracks][2][duration] | 682 |
      | music_album[tracks][3][title] | Shep |
      | music_album[tracks][3][duration] | 624 |
      | music_album[tracks][4][title] | Pigs on the Wing 2 |
      | music_album[tracks][4][duration] | 86 |
    And I press the "Save" button
    Then I should be on "/music/"
    And I should see "Album edited successfully !"
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    And the "music_album[tracks][0][title]" field should contain "Pigs on the Wing 1"
    And the "music_album[tracks][1][title]" field should contain "Dogs"
    And the "music_album[tracks][2][title]" field should contain "Pigs (Three Different Ones)"
    And the "music_album[tracks][3][title]" field should contain "Shep"
    And the "music_album[tracks][4][title]" field should contain "Pigs on the Wing 2"

  Scenario: Edit track album
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    When I fill in the following:
      | music_album[tracks][3][title] | Sheep |
    And I press the "Save" button
    Then I should be on "/music/"
    And I should see "Album edited successfully !"
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    And the "music_album[tracks][0][title]" field should contain "Pigs on the Wing 1"
    And the "music_album[tracks][1][title]" field should contain "Dogs"
    And the "music_album[tracks][2][title]" field should contain "Pigs (Three Different Ones)"
    And the "music_album[tracks][3][title]" field should contain "Sheep"
    And the "music_album[tracks][4][title]" field should contain "Pigs on the Wing 2"

  Scenario: Delete track album
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    # Strange, it should be the 4th
#    When I press the 4th "Delete" button
    When I press the 5th "Delete" button
    And I press the "Save" button
    Then I should be on "/music/"
    And I should see "Album edited successfully !"
    Given I am on "/music/1/edit"
    Then the "music_album[artist]" field should contain "Pink Floyd"
    And the "music_album[tracks][0][title]" field should contain "Pigs on the Wing 1"
    And the "music_album[tracks][1][title]" field should contain "Dogs"
    And the "music_album[tracks][2][title]" field should contain "Pigs (Three Different Ones)"
    And the "music_album[tracks][3][title]" field should contain "Pigs on the Wing 2"

  Scenario: Delete album
    Given I am on "/music"
    Then I should see "Pink Floyd"
    And I should see "Animals"
    When I press the 1st "Delete" link
#    Then I should be on "/music/1/remove"
    Then I should be on "/music/"
    When I wait 1 second
    And I should see "Do you really want to remove \"Animals\" ?"
    When I press the "Yes" button
    Then I should be on "/music/"
    And I should see "Album deleted successfully !"
    And I should not see "Pink Floyd"
    And I should not see "Animals"
    And I should see "No album"
