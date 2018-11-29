Feature: Use an Oracle
    Ask an Oracle to generate 6 NPC (just name / folk) with a balance between
    gender

# TODO
# [ ] data au niveau du Choice
# [ ] grand retour du resolve next
# [ ] gestion des tags

# Need to identify easily witch choice to resolve inside a Bible
# specific data pass to Choices ? gender & folk ?
# Choice level or bible level ?
# → information stockée dans le Choice, mais mise en place au niveau de Bible
# genre, à l'ajout du choix, on peux passer des données suplémentaires
  Background:
    Given the "gender" Choice
        | name     | weight | tag | resolve_next |
        | masculin | 400    | +♂  |              |
        | feminin  | 400    | +♀  |              |
        | plus     | 50     |     | gender_more  |
    And the "gender_more" Choice
        | name         | weight |
        | ambigu       | 150    |
        | dissimulé    | 10     |
        | transgressif | 100    |
        | animal       | 20     |
        | esoterique   | 5      |
        | fluide       | 100    |
    And the "folk" Choice
        | name         | weight | resolve_next     |
        | Hoszuhu      | 100    | hoszuhu_name     |
        | Mérovingien  | 100    | merovingien_name |
        | AuFant Rhuul | 100    | aufantrhuul_name |
    And the "hoszuhu_name" Choice
        | name    | weight | tag  |
        | Aanu    | 200    | ♂:*0 |
        | Eine    | 300    | ♂:*0 |
        | Ilta    | 200    | ♂:*0 |
        | Kielo   | 100    | ♂:*0 |
        | Sohvi   | 200    | ♂:*0 |
        | Arvo    | 100    | ♀:*0 |
        | Okko    | 200    | ♀:*0 |
        | Otso    | 100    | ♀:*0 |
        | Seppo   | 100    | ♀:*0 |
        | Tarvo   | 50     | ♀:*0 |
    And the "merovingien_name" Choice
        | name      | weight | tag  |
        | Séraphine | 100    | ♂:*0 |
        | Huguette  | 50     | ♂:*0 |
        | Séverine  | 100    | ♂:*0 |
        | Clémence  | 100    | ♂:*0 |
        | Eugénie   | 10     | ♂:*0 |
        | Marius    | 100    | ♀:*0 |
        | Jacques   | 100    | ♀:*0 |
        | Ernest    | 100    | ♀:*0 |
        | Mathis    | 80     | ♀:*0 |
        | Joseph    | 100    | ♀:*0 |
    And the "aufantrhuul_name" Choice
        | name       | weight | tag  |
        | crilo      | 100    |      |
        | iercal     | 100    |      |
        | aishescish | 10     |      |
        | racsasix   | 100    |      |
        | axzikke    | 100    |      |
        | erya       | 100    |      |
    And the "npc" Bible
        | choiceName       | data    |
        | gender           | starter |
        | gender_more      |         |
        | folk             | starter |
        | hoszuhu_name     |         |
        | merovingien_name |         |
        | aufantrhuul_name |         |
    And the "sixNPC" Oracle who resolve 6 times the "npc" Bible

  Scenario: Create Six NPC
    When I ask the Oracle "sixNPC"
    Then The Oracle "sixNPC" should have 6 "npc"
