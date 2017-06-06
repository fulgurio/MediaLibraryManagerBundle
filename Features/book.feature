Feature: Manager book
  -----
  Route:
  - FulgurioMLM_Book_Add
  - FulgurioMLM_Book_Edit
  - FulgurioMLM_Book_Remove
  - FulgurioMLM_Book_List

  @database

  Scenario: Add new book
    Given I am on "/"
    When I follow the "Books" link
    Then I should be on "/book/"
    And I should see "No book"
    When I follow the "Add new book" link
    Then I should be on "/book/add"
    And I should see "Author"
    And I should see "Book name"
    And I should see "Media type"
    And I should see "EAN"
    And I should see "Publication year"
    And I should see "Publisher"
    And I should see "Cover"
#    When I press the "Save" button
#    Then I should be on "/book/add"
#    And I should see "Author is required"
#    And I should see "Title is required"
    When I fill in the following:
      | book[author] | Dan Brown  |
      | book[title] | Da Vici Code |
      | book[publication_year] | 2003 |
      | book[ean] | 9782709624930 |
      | book[publisher] | Doubleday |
    And I press the "Save" button
    Then I should be on "/book/"
    And I should see "New book added successfully !"
    And I should see "Dan Brown"
    And I should see "Da Vici Code"

  Scenario: Search book
    Given I am on "/book"
    Then I should see "Dan Brown"
    When I fill in the following:
      | q | Orwell |
    And I press the "Search" button
    Then I should see "No result found"
    When I fill in the following:
      | q | Dan |
    And I press the "Search" button
    Then I should see "Dan Brown"

  Scenario: Edit book
    Given I am on "/book"
    Then I should see "Dan Brown"
    And I should see "Da Vici Code"
    And I should not see "Da Vinci Code"
    When I press the 1st "Edit" link
    Then I should be on "/book/1/edit"
    When I fill in the following:
      | book[title] | Da Vinci Code |
    And I press the "Save" button
    Then I should be on "/book/"
    And I should see "Book edited successfully !"
    And I should see "Dan Brown"
    And I should not see "Da Vici Code"
    And I should see "Da Vinci Code"

  Scenario: Delete book
    Given I am on "/book"
    Then I should see "Dan Brown"
    And I should see "Da Vinci Code"
    When I press the 1st "Delete" link
#    Then I should be on "/book/1/remove"
    Then I should be on "/book/"
    When I wait 1 second
    And I should see "Do you really want to remove \"Da Vinci Code\" ?"
    When I press the "Yes" button
    Then I should be on "/book/"
    And I should see "Book deleted successfully !"
    And I should not see "Dan Brown"
    And I should not see "Da Vinci Code"
    And I should see "No book"
