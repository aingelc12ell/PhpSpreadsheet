<?php

return [
    // Basic arithmetic
    ['=D3+F7+G4+C6+5', '=R3C4+R7C6+R4C7+R6C3+5'],
    ['=D3-F7-G4-C6-5', '=R3C4-R7C6-R4C7-R6C3-5'],
    ['=D3*F7*G4*C6*5', '=R3C4*R7C6*R4C7*R6C3*5'],
    ['=D3/F7/G4/C6/5', '=R3C4/R7C6/R4C7/R6C3/5'],
    // Formulas
    ['=SUM(E1:E5)', '=SUM(R1C5:R5C5)'],
    ['=SUM(E1:E5, D5)', '=SUM(R1C5:R5C5, R5C4)'],
    ['=SUM(E1:E5, D5)-C5', '=SUM(R1C5:R5C5, R5C4)-R5C3'],
    ['=IF(E1>E2, E3, E4)', '=IF(R1C5>R2C5, R3C5, R4C5)'],
    // String literals
    ['=CONCAT("Result of formula expression =R3C3+R4C3 is: ", C3+C4)', '=CONCAT("Result of formula expression =R3C3+R4C3 is: ", R3C3+R4C3)'],
];
