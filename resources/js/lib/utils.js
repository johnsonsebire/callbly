/**
 * Conditionally join class names together
 * This utility helps with conditional application of CSS class names
 * 
 * @param  {...string} classes - Class names to conditionally join
 * @returns {string} - Joined class names
 */
export function cn(...classes) {
  return classes.filter(Boolean).join(' ');
}