UPDATE `wp_aha_assessment_school` s
JOIN `wp_aha_assessment_school_update` u
ON s.`DIST_ID` = u.`DIST_ID`
SET 
s.`RANK` = u.`RANK`,
s.`TOTAL_ENROLLED_STUDENTS` = u.`TOTAL_ENROLLED_STUDENTS`,
s.`ELEM` = u.`ELEM`,
s.`MIDD` = u.`MIDD`,
s.`HIGH` = u.`HIGH`,
s.`2.1.4.1.1` = u.`2.1.4.1.1`,
s.`2.1.4.1.2` = u.`2.1.4.1.2`,
s.`2.1.4.1.3` = u.`2.1.4.1.3`,
s.`2.2.5.1` = u.`2.2.5.1`,
s.`2.2.5.1.2` = u.`2.2.5.1.2`,
s.`2.2.5.1.3` = u.`2.2.5.1.3`,
s.`3.1.2` = u.`3.1.2`,
s.`3.1.3.1.0` = u.`3.1.3.1.0`,
s.`3.1.3.1.1` = u.`3.1.3.1.1`,
s.`3.1.3.1.2` = u.`3.1.3.1.2`,
s.`3.1.3.1.3` = u.`3.1.3.1.3`,
s.`3.1.3.1.4` = u.`3.1.3.1.4`,
s.`3.2.1.1` = u.`3.2.1.1`;
