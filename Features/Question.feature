Feature: Allow a user to ask questions and allow admins to answer and solve them
    Scenario: Create a question
        Given I am logged in as a user
        And I am on '/support'
        And I press "Ask a Question"
        And I fill in "Title" with "My first question"
        And I fill in "Question Details" with "I dont know how to do this"
        And I press "Submit Question"
        Then I should see "Question created"

    Scenario: View a question
        Given I am logged in as a user
        And I am on '/support'
        Then I should see 'My first question'

