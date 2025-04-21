const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 1000,
};

ScrollReveal().reveal(".header__container h2", {
  ...scrollRevealOption,
});
ScrollReveal().reveal(".header__container h1", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".header__container p", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__btns", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".steps__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".explore__card", {
  duration: 1000,
  interval: 500,
});

ScrollReveal().reveal(".job__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".offer__card", {
  ...scrollRevealOption,
  interval: 500,
});

const swiper = new Swiper(".swiper", {
  loop: true,
});

// Add this code to your main.js file

// Job Search Modal Functionality
const browseJobsBtn = document.querySelector(".header__btns .btn");
const jobSearchModal = document.getElementById("jobSearchModal");
const closeSearchBtn = document.getElementById("closeSearchBtn");
const searchJobsBtn = document.getElementById("searchJobsBtn");
const jobResults = document.getElementById("jobResults");
const resultCount = document.getElementById("resultCount");
const salaryRange = document.getElementById("salaryRange");
const salaryValue = document.getElementById("salaryValue");

// Sample job data - in a real application this would come from a backend
const jobsData = [
  {
    id: 1,
    title: "Senior Product Engineer",
    company: "Figma",
    location: "San Francisco, USA",
    type: "Full Time",
    experience: "Senior Level",
    salary: 145000,
    posted: "2 days ago",
    logo: "assets/figma.png",
    description: "As a Senior Product Engineer at Figma, you'll lead the development of innovative product solutions, leveraging your expertise in engineering and product management to drive success. You'll work closely with designers and product managers to create intuitive and powerful features for our design platform.",
    requirements: [
      "7+ years of experience in product engineering",
      "Strong background in JavaScript and TypeScript",
      "Experience with modern frontend frameworks (React, Vue, Angular)",
      "Bachelor's degree in Computer Science or related field",
      "Excellent problem-solving and communication skills"
    ],
    tags: ["Engineering", "Product", "Senior", "Remote"]
  },
  {
    id: 2,
    title: "Project Manager",
    company: "Google",
    location: "Austin, USA",
    type: "Full Time",
    experience: "Mid-Level",
    salary: 95000,
    posted: "1 week ago",
    logo: "assets/google.png",
    description: "As a Project Manager at Google, you'll manage project timelines and budgets to ensure successful delivery of projects on schedule, while maintaining clear communication with stakeholders. You'll coordinate cross-functional teams and ensure that project goals align with business objectives.",
    requirements: [
      "5+ years of project management experience",
      "PMP certification preferred",
      "Experience with Agile methodologies",
      "Strong leadership and communication skills",
      "Bachelor's degree in Business or related field"
    ],
    tags: ["Project Management", "Agile", "Leadership"]
  },
  {
    id: 3,
    title: "Full Stack Developer",
    company: "LinkedIn",
    location: "Berlin, Germany",
    type: "Full Time",
    experience: "Entry Level",
    salary: 35000,
    posted: "3 days ago",
    logo: "assets/linkedin.png",
    description: "As a Full Stack Developer at LinkedIn, you'll develop and maintain both front-end and back-end components of web applications, utilizing a wide range of programming languages and frameworks. You'll collaborate with cross-functional teams to build scalable solutions for our professional networking platform.",
    requirements: [
      "2+ years of full-stack development experience",
      "Proficiency in JavaScript, HTML, CSS, and at least one backend language",
      "Experience with React, Node.js, and SQL/NoSQL databases",
      "Knowledge of RESTful APIs and microservices architecture",
      "Bachelor's degree in Computer Science or equivalent experience"
    ],
    tags: ["Full Stack", "JavaScript", "React", "Node.js"]
  },
  {
    id: 4,
    title: "Front-end Developer",
    company: "Amazon",
    location: "Seattle, USA",
    type: "Full Time",
    experience: "Mid-Level",
    salary: 101000,
    posted: "5 days ago",
    logo: "assets/amazon.png",
    description: "As a Front-end Developer at Amazon, you'll design and implement user interfaces using HTML, CSS, and JavaScript, collaborating closely with designers and back-end developers. You'll build responsive and accessible web interfaces that provide exceptional user experiences for millions of customers.",
    requirements: [
      "4+ years of front-end development experience",
      "Strong proficiency in HTML, CSS, JavaScript, and modern frameworks",
      "Experience with responsive design and cross-browser compatibility",
      "Knowledge of performance optimization techniques",
      "Bachelor's degree in Computer Science or equivalent experience"
    ],
    tags: ["Front-end", "React", "UI/UX", "JavaScript"]
  },
  {
    id: 5,
    title: "ReactJS Developer",
    company: "Twitter",
    location: "Remote, USA",
    type: "Full Time",
    experience: "Mid-Level",
    salary: 98000,
    posted: "1 day ago",
    logo: "assets/twitter.png",
    description: "As a ReactJS Developer at Twitter, you'll specialize in building dynamic and interactive user interfaces using the ReactJS library, leveraging your expertise in JavaScript and front-end development. You'll work on high-visibility projects that impact millions of users worldwide.",
    requirements: [
      "3+ years of experience with React.js",
      "Strong understanding of JavaScript, HTML, and CSS",
      "Experience with state management libraries (Redux, MobX)",
      "Knowledge of modern build tools and workflows",
      "Bachelor's degree in Computer Science or equivalent experience"
    ],
    tags: ["React", "JavaScript", "Front-end", "Redux"]
  },
  {
    id: 6,
    title: "Python Developer",
    company: "Microsoft",
    location: "Redmond, USA",
    type: "Full Time",
    experience: "Entry Level",
    salary: 80000,
    posted: "4 days ago",
    logo: "assets/microsoft.png",
    description: "As a Python Developer at Microsoft, you'll develop scalable and efficient backend systems and applications using Python, utilizing your proficiency in Python programming and software development. You'll work on core infrastructure components that power Microsoft's cloud services.",
    requirements: [
      "2+ years of experience with Python",
      "Knowledge of Python frameworks (Django, Flask)",
      "Understanding of RESTful APIs and microservices",
      "Experience with databases (SQL and NoSQL)",
      "Bachelor's degree in Computer Science or equivalent experience"
    ],
    tags: ["Python", "Backend", "Django", "Cloud"]
  }
];


browseJobsBtn.addEventListener("click", () => {
  jobSearchModal.classList.add("open");
  document.body.style.overflow = "hidden"; 
  populateJobResults(jobsData); 
});

closeSearchBtn.addEventListener("click", () => {
  jobSearchModal.classList.remove("open");
  document.body.style.overflow = "auto"; 
});


salaryRange.addEventListener("input", () => {
  salaryValue.textContent = `$${parseInt(salaryRange.value).toLocaleString()}+`;
});


searchJobsBtn.addEventListener("click", () => {
  const keyword = document.getElementById("jobKeyword").value.toLowerCase();
  const location = document.getElementById("jobLocation").value.toLowerCase();
  const category = document.getElementById("jobCategory").value.toLowerCase();
  const minSalary = parseInt(salaryRange.value);
  

  const selectedJobTypes = Array.from(document.querySelectorAll('.filter__group input[type="checkbox"]:checked'))
    .filter(cb => cb.closest('.filter__group').querySelector('span').textContent === "Job Type:")
    .map(cb => cb.value);
  
  
  const selectedExperience = Array.from(document.querySelectorAll('.filter__group input[type="checkbox"]:checked'))
    .filter(cb => cb.closest('.filter__group').querySelector('span').textContent === "Experience:")
    .map(cb => cb.value);
  
  
  const filteredJobs = jobsData.filter(job => {
    const matchesKeyword = keyword === '' || 
      job.title.toLowerCase().includes(keyword) || 
      job.company.toLowerCase().includes(keyword) ||
      job.description.toLowerCase().includes(keyword);
      
    const matchesLocation = location === '' || job.location.toLowerCase().includes(location);
    
    
    const matchesCategory = category === '' || job.tags.some(tag => tag.toLowerCase().includes(category));
    
    const matchesSalary = job.salary >= minSalary;
    
    const matchesJobType = selectedJobTypes.length === 0 || 
      selectedJobTypes.some(type => job.type.toLowerCase().includes(type));
      
    const matchesExperience = selectedExperience.length === 0 || 
      selectedExperience.some(exp => job.experience.toLowerCase().includes(exp));
    
    return matchesKeyword && matchesLocation && matchesCategory && 
           matchesSalary && matchesJobType && matchesExperience;
  });
  
  populateJobResults(filteredJobs);
});


function populateJobResults(jobs) {
  jobResults.innerHTML = '';
  resultCount.textContent = `(${jobs.length})`;
  
  if (jobs.length === 0) {
    jobResults.innerHTML = `
      <div class="no-results">
        <i class="ri-search-line" style="font-size: 3rem; color: var(--primary-color);"></i>
        <h4>No jobs found</h4>
        <p>Try adjusting your search criteria</p>
      </div>
    `;
    return;
  }
  
  jobs.forEach(job => {
    const jobCard = document.createElement('div');
    jobCard.classList.add('job__result__card');
    
    const requirementsList = job.requirements.map(req => `<li>${req}</li>`).join('');
    const tagsList = job.tags.map(tag => `<span class="job__tag">${tag}</span>`).join('');
    
    jobCard.innerHTML = `
      <div class="job__result__header">
        <div class="job__company">
          <img src="${job.logo}" alt="${job.company}">
          <div class="job__company__info">
            <h4>${job.company}</h4>
            <p>${job.location}</p>
          </div>
        </div>
        <p>Posted: ${job.posted}</p>
      </div>
      <div class="job__result__body">
        <h3>${job.title}</h3>
        <p class="job__description">${job.description}</p>
        
        <div class="job__details">
          <div class="job__detail">
            <i class="ri-briefcase-line"></i>
            <span>${job.type}</span>
          </div>
          <div class="job__detail">
            <i class="ri-user-star-line"></i>
            <span>${job.experience}</span>
          </div>
          <div class="job__detail">
            <i class="ri-money-dollar-circle-line"></i>
            <span>$${job.salary.toLocaleString()}/year</span>
          </div>
          <div class="job__detail">
            <i class="ri-timer-line"></i>
            <span>Apply by May 15, 2025</span>
          </div>
        </div>
        
        <div class="job__requirements">
          <h5>Requirements:</h5>
          <ul>
            ${requirementsList}
          </ul>
        </div>
      </div>
      <div class="job__result__footer">
        <div class="job__tags">
          ${tagsList}
        </div>
        <button class="btn">Apply Now</button>
      </div>
    `;
    
    jobResults.appendChild(jobCard);
  });
}


jobSearchModal.addEventListener("click", (e) => {
  if (e.target === jobSearchModal) {
    jobSearchModal.classList.remove("open");
    document.body.style.overflow = "auto";
  }
});

document.querySelector(".job__search__container").addEventListener("click", (e) => {
  e.stopPropagation();
});




const jobCards = document.querySelectorAll('.job__card');

jobCards.forEach(card => {
  card.addEventListener('click', () => {
    
    jobSearchModal.classList.add('open');
    document.body.style.overflow = "hidden";
    
    
    const jobTitle = card.querySelector('h4').textContent;
    
  
    const matchingJobs = jobsData.filter(job => 
      job.title === jobTitle || job.title.includes(jobTitle)
    );
    
    
    populateJobResults(matchingJobs);
    
    
    document.getElementById('jobKeyword').value = jobTitle;
  });
});


const viewAllCategoriesBtn = document.querySelector('.explore__btn .btn');
viewAllCategoriesBtn.addEventListener('click', () => {
  jobSearchModal.classList.add('open');
  document.body.style.overflow = "hidden";
  populateJobResults(jobsData);
});


const categoryCards = document.querySelectorAll('.explore__card');
categoryCards.forEach(card => {
  card.addEventListener('click', () => {
    // Open job search modal
    jobSearchModal.classList.add('open');
    document.body.style.overflow = "hidden";
    
    
    const category = card.querySelector('h4').textContent;
    

    const matchingJobs = jobsData.filter(job => 
      job.tags.some(tag => tag.toLowerCase() === category.toLowerCase() || 
                          tag.toLowerCase().includes(category.toLowerCase()))
    );
    
    
    populateJobResults(matchingJobs.length > 0 ? matchingJobs : jobsData);
    
    
    const categorySelect = document.getElementById('jobCategory');
    const categoryOption = Array.from(categorySelect.options).find(option => 
      option.text.toLowerCase() === category.toLowerCase()
    );
    
    if (categoryOption) {
      categorySelect.value = categoryOption.value;
    }
  });
});