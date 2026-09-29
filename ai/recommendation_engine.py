import os
import sys
import mysql.connector
from mysql.connector import Error

def get_db_connection():
    try:
        connection = mysql.connector.connect(
            host=os.getenv('MASARAK_DB_HOST', '127.0.0.1'),
            database=os.getenv('MASARAK_DB_NAME', 'masarak_db'),
            user=os.getenv('MASARAK_DB_USER', 'masarak'),
            password=os.getenv('MASARAK_DB_PASSWORD', '')
        )
        return connection
    except Error as e:
        print(f"Error connecting to MySQL: {e}")
        return None

def calculate_match_score(weighted_score, preferred_field, interest_nature, work_environment, major):
    """Calculate match score based on multiple factors"""
    score = 0.0
    
    # Convert Decimal to float for calculations
    min_score = float(major['min_weighted_score']) if major['min_weighted_score'] is not None else 0.0
    
    # Academic score weight (40%)
    academic_weight = 0.4
    if weighted_score >= min_score:
        score += academic_weight * 100
    else:
        # Penalty for low academic score
        if min_score > 0:
            score += academic_weight * (weighted_score / min_score) * 100
        else:
            score += academic_weight * 50  # Default score if min_score is 0
    
    # Field preference weight (30%)
    field_weight = 0.3
    if major['field_category'] == preferred_field:
        score += field_weight * 100
    else:
        # Partial match for related fields
        related_fields = {
            'technology': ['engineering'],
            'engineering': ['technology'],
            'health': [],
            'arts': ['tourism'],
            'tourism': ['arts'],
            'law': []
        }
        if preferred_field in related_fields.get(major['field_category'], []):
            score += field_weight * 50
    
    # Interest nature weight (20%)
    interest_weight = 0.2
    interest_matches = {
        'analytical': ['technology', 'engineering', 'law'],
        'creative': ['arts', 'tourism'],
        'helping_others': ['health', 'law']
    }
    if major['field_category'] in interest_matches.get(interest_nature, []):
        score += interest_weight * 100
    else:
        score += interest_weight * 30
    
    # Work environment weight (10%)
    env_weight = 0.1
    env_matches = {
        'office': ['technology', 'law'],
        'field': ['engineering', 'tourism'],
        'laboratory': ['health', 'engineering'],
        'creative_space': ['arts']
    }
    if major['field_category'] in env_matches.get(work_environment, []):
        score += env_weight * 100
    else:
        score += env_weight * 50
    
    return min(score, 100.0)

def get_acceptance_probability(weighted_score, min_score):
    """Determine acceptance probability"""
    # Convert Decimal to float for calculations
    min_score = float(min_score) if min_score is not None else 0.0
    
    if min_score == 0:
        return 'high'  # If no minimum score set, assume high probability
    
    if weighted_score >= min_score * 1.1:
        return 'high'
    elif weighted_score >= min_score * 0.9:
        return 'medium'
    else:
        return 'low'

def get_recommendation_reason(major, match_score, weighted_score, lang='ar'):
    """Generate recommendation reason"""
    reasons_ar = {
        'technology': f'معدلك الموزون ({weighted_score:.2f}) مناسب لهذا التخصص التقني. تفضيلاتك وميولك تتوافق مع متطلبات هذا المجال.',
        'health': f'معدلك الموزون ({weighted_score:.2f}) يسمح لك بدراسة هذا التخصص الصحي. ميولك في مساعدة الآخرين يجعل هذا التخصص مناسباً لك.',
        'engineering': f'معدلك الموزون ({weighted_score:.2f}) مناسب للتخصصات الهندسية. طبيعة اهتمامك التحليلية تتوافق مع هذا المجال.',
        'arts': f'معدلك الموزون ({weighted_score:.2f}) مناسب لهذا التخصص الفني. ميولك الإبداعية تجعل هذا التخصص خياراً جيداً.',
        'tourism': f'معدلك الموزون ({weighted_score:.2f}) مناسب لهذا التخصص السياحي. تفضيلاتك في بيئة العمل الميدانية تتوافق مع هذا المجال.',
        'law': f'معدلك الموزون ({weighted_score:.2f}) مناسب لهذا التخصص القانوني. طبيعة اهتمامك التحليلية وميولك في مساعدة الآخرين يجعلان هذا التخصص مناسباً.'
    }
    
    reasons_en = {
        'technology': f'Your weighted score ({weighted_score:.2f}) is suitable for this technical major. Your preferences and interests align with this field\'s requirements.',
        'health': f'Your weighted score ({weighted_score:.2f}) allows you to study this health major. Your interest in helping others makes this major suitable for you.',
        'engineering': f'Your weighted score ({weighted_score:.2f}) is suitable for engineering majors. Your analytical interest nature aligns with this field.',
        'arts': f'Your weighted score ({weighted_score:.2f}) is suitable for this arts major. Your creative interests make this major a good choice.',
        'tourism': f'Your weighted score ({weighted_score:.2f}) is suitable for this tourism major. Your preference for field work environment aligns with this field.',
        'law': f'Your weighted score ({weighted_score:.2f}) is suitable for this law major. Your analytical interest nature and interest in helping others make this major suitable.'
    }
    
    if lang == 'ar':
        return reasons_ar.get(major['field_category'], f'معدلك الموزون ({weighted_score:.2f}) مناسب لهذا التخصص.')
    else:
        return reasons_en.get(major['field_category'], f'Your weighted score ({weighted_score:.2f}) is suitable for this major.')

def generate_recommendations(user_id, weighted_score, preferred_field, interest_nature, work_environment):
    """Generate recommendations for user"""
    connection = get_db_connection()
    if not connection:
        return
    
    try:
        cursor = connection.cursor(dictionary=True)
        
        # Get all majors
        cursor.execute("SELECT * FROM majors")
        majors = cursor.fetchall()
        
        # Calculate scores for all majors
        major_scores = []
        for major in majors:
            match_score = calculate_match_score(weighted_score, preferred_field, interest_nature, work_environment, major)
            major_scores.append({
                'major': major,
                'match_score': match_score
            })
        
        # Sort by match score
        major_scores.sort(key=lambda x: x['match_score'], reverse=True)
        
        # Get top 5 recommendations
        top_majors = major_scores[:5]
        
        # Clear old recommendations
        cursor.execute("DELETE FROM recommendations WHERE user_id = %s", (user_id,))
        
        # Insert new recommendations
        for item in top_majors:
            major = item['major']
            match_score = item['match_score']
            # Convert Decimal to float for acceptance probability calculation
            min_score = float(major['min_weighted_score']) if major['min_weighted_score'] is not None else 0.0
            acceptance_prob = get_acceptance_probability(weighted_score, min_score)
            reason_ar = get_recommendation_reason(major, match_score, weighted_score, 'ar')
            reason_en = get_recommendation_reason(major, match_score, weighted_score, 'en')
            
            cursor.execute("""
                INSERT INTO recommendations 
                (user_id, major_name_ar, major_name_en, college_ar, college_en, 
                 recommendation_reason_ar, recommendation_reason_en, acceptance_probability, match_score)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                user_id,
                major['major_name_ar'],
                major['major_name_en'],
                major['college_ar'],
                major['college_en'],
                reason_ar,
                reason_en,
                acceptance_prob,
                match_score
            ))
        
        connection.commit()
        print("Recommendations generated successfully")
        
    except Error as e:
        print(f"Error generating recommendations: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

if __name__ == "__main__":
    if len(sys.argv) < 6:
        print("Usage: python recommendation_engine.py <user_id> <weighted_score> <preferred_field> <interest_nature> <work_environment>")
        sys.exit(1)
    
    user_id = int(sys.argv[1])
    weighted_score = float(sys.argv[2])
    preferred_field = sys.argv[3]
    interest_nature = sys.argv[4]
    work_environment = sys.argv[5]
    
    generate_recommendations(user_id, weighted_score, preferred_field, interest_nature, work_environment)
