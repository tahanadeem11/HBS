/**
 * View More Content Script
 * Automatically handles hiding long content after the first paragraph
 * on specific pages and adds a toggle button.
 */

document.addEventListener('DOMContentLoaded', function () {
    const targetSelectors = [
        '.single-blog-style1 .text',
        '.service-details-main-text',
        '.service-details-text-1'
    ];

    targetSelectors.forEach(selector => {
        const containers = document.querySelectorAll(selector);

        containers.forEach(container => {
            const paragraphs = container.querySelectorAll('p, strong, div:not(.view-more-btn-wrapper)');

            // Only apply if there's more than one content element
            if (paragraphs.length > 1) {
                // Add a wrapper for the hidden content if not already present
                if (!container.querySelector('.view-more-content')) {
                    const viewMoreContent = document.createElement('div');
                    viewMoreContent.className = 'view-more-content';
                    viewMoreContent.style.display = 'none';

                    // Move all elements after the first paragraph into the hidden container
                    // Using index 1 to keep the first element visible
                    for (let i = 1; i < paragraphs.length; i++) {
                        // Check if the element is a direct child of the container
                        if (paragraphs[i].parentElement === container) {
                            viewMoreContent.appendChild(paragraphs[i]);
                        }
                    }

                    container.appendChild(viewMoreContent);

                    // Add the View More button
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = 'view-more-btn-wrapper';
                    btnWrapper.innerHTML = `
                        <button class="view-more-btn btn-one">
                            <span class="txt">View More</span>
                        </button>
                    `;

                    container.appendChild(btnWrapper);

                    const btn = btnWrapper.querySelector('.view-more-btn');
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const content = container.querySelector('.view-more-content');
                        const isExpanded = content.style.display === 'block';

                        if (isExpanded) {
                            content.style.display = 'none';
                            this.querySelector('.txt').textContent = 'View More';
                            // Scroll back to the container top if needed
                            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        } else {
                            content.style.display = 'block';
                            this.querySelector('.txt').textContent = 'View Less';
                        }
                    });
                }
            }
        });
    });
});
