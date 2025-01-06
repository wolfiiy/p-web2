/**
 * ETML
 * Author: Sebastien Tille
 * Date: August 20th, 2020
 * Description: Lets the user toggle between two themes. The preference is
 * stored in the browser's local storage.
 * Note: Taken from a previous project.
 * See https://gitlab.com/wolfiy/wlfys-minimal-startpage
 */

/**
 * Dark mode toggle
 */
const darkToggle = document.getElementById('dark-mode-toggle')

/**
 * Whether the dark mode is on. By default, dark mode is enabled.
 */
let isDark = localStorage.getItem('isDark') || 1

/**
 * Load theme on page load.
 */
if (isDark == 1) toggleDarkMode(true)

/**
 * Toggles dark mode on or off.
 * @param {boolean} isDark true to set dark mode, false to set light mode.
 */
function toggleDarkMode(isDark) {
  if (isDark) {
    document.body.classList.add('dark')
    localStorage.setItem('isDark', 1)
  } else {
    document.body.classList.remove('dark')
    localStorage.setItem('isDark', 0)
  }
}

/**
 * Listen for clicks on the dark mode toggle.
 */
darkToggle.addEventListener('click', () => {
  isDark = localStorage.getItem('isDark')
  isDark == 1 ? toggleDarkMode(false) 
              : toggleDarkMode(true)
})