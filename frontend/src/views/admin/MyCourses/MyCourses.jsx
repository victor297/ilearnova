import React from 'react';
import ReactDOM from 'react-dom/client';
import image1 from 'assets/img/VideoImage/image1.png';
import image2 from 'assets/img/VideoImage/image2.png';
import image3 from 'assets/img/VideoImage/image3.png';
import image4 from 'assets/img/VideoImage/image4.png';
import image5 from 'assets/img/VideoImage/image5.png';
import image6 from 'assets/img/VideoImage/image6.png';


// Sample course data
const courseData = [
  {
    title: 'Introduction to Physics',
    teacher: 'Teacher A',
    lessons: 24,
    time: '3 hrs 15mins',
    progress: 80,
    image: image1
  },
  {
    title: 'Introduction to Chemistry',
    teacher: 'Teacher B',
    lessons: 20,
    time: '3 hrs 10mins',
    progress: 80,
    image: image2
  },
  {
    title: 'Use Of English',
    teacher: 'Teacher C',
    lessons: 30,
    time: '4 hrs',
    progress: 80,
    image: image3
  },
  {
    title: 'Mathematics: Simultaneous Equation',
    teacher: 'Teacher A',
    lessons: 28,
    time: '3 hrs 12mins',
    progress: 80,
    image: image4
  },
  {
    title: 'Advance Mathematics: DY/DX Solutions',
    teacher: 'Teacher B',
    lessons: 21,
    time: '3 hrs 8mins',
    progress: 80,
    image: image5
  },
  {
    title: 'Biology: Reproduction',
    teacher: 'Teacher C',
    lessons: 32,
    time: '3 hrs 45mins',
    progress: 80,
    image: image6
  }
];

// Card component
const CourseCard = ({ course }) => {
  return (
    <div className="course-card">
      <div className="video-image-container">
        <img src={course.image} alt={course.title} className="course-image" />
        <div className="video-icon">▶</div>
      </div>
      <div className="course-info">
        <h3>{course.title}</h3>
        <p>{course.teacher}</p>
        <p>{course.lessons} Lessons - {course.time}</p>
        <progress value={course.progress} max="100"></progress>
      </div>
        </div>
  );
};

// Main Page component
const MyCourses = () => {
  return (
    <div className="container mx-auto p-4">
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <div className="course-container">
      {courseData.map((course, index) => (
        <CourseCard key={index} course={course} />
      ))}
      </div>
      </div>
    </div>
  );
};


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <MyCourses />
  </React.StrictMode>
);

export default MyCourses;