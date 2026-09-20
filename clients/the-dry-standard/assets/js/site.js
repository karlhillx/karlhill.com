import { chrome } from "./chrome.js";
import { directory } from "./directory.js";
import { archive } from "./archive.js";
import { compareTray } from "./compare.js";
import { saveTray } from "./save.js";
import { reviewSticky, reviewSubnav } from "./review.js";
import { analytics } from "./analytics.js";
import { inPageJump } from "./nav.js";
import { reveal } from "./reveal.js";
import { cardRails } from "./rails.js";

chrome();
directory();
archive();
compareTray();
saveTray();
reviewSticky();
reviewSubnav();
analytics();
inPageJump();
// reveal() opts into html.js only after marking on-screen sections — do not add js earlier.
reveal();
cardRails();
